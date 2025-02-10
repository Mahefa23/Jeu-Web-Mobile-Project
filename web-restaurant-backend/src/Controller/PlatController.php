<?php

namespace App\Controller;

use App\Entity\Plat;
use App\Entity\Recette;
use App\Entity\Ingredient;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/plat')]
class PlatController extends AbstractController
{

    // Lister tous les plats
    #[Route('/', name: 'plat_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): JsonResponse
    {
        // Récupérer tous les plats
        $plats = $entityManager->getRepository(Plat::class)->findAll();
        
        $platData = [];
        foreach ($plats as $plat) {
            // Récupérer les ingrédients associés au plat
            $ingredients = $plat->getIngredients()->toArray(); 

            // Ajouter les informations du plat, y compris les ingrédients et l'URL de l'image
            $platData[] = [
                'id' => $plat->getId(),
                'name' => $plat->getName(),
                'cooking_time' => $plat->getCookingTime(),
                'prix' => $plat->getPrix(),
                'ingredients' => array_map(function ($ingredient) {
                    return [
                        'id' => $ingredient->getId(),
                        'name' => $ingredient->getName(),
                        'stock' => $ingredient->getStock(),
                    ];
                }, $ingredients)
            ];
        }

        return $this->json($platData);
    }


    // CREATE - Ajouter un plat avec des ingrédients existants
    #[Route('/new', name: 'plat_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (isset($data['name'], $data['cooking_time'], $data['prix'], $data['ingredients'])) {
            // Créer le plat
            $plat = new Plat();
            $plat->setName($data['name']);
            $plat->setCookingTime($data['cooking_time']);
            $plat->setPrix($data['prix']);

            // Charger les ingrédients existants dans la base de données
            $ingredientRepository = $entityManager->getRepository(Ingredient::class);
            $ingredients = $ingredientRepository->findBy(['id' => $data['ingredients']]);

            // Vérifier que les ingrédients existent dans la base de données
            if (count($ingredients) !== count($data['ingredients'])) {
                return $this->json(['error' => 'Un ou plusieurs ingrédients n\'existent pas'], JsonResponse::HTTP_BAD_REQUEST);
            }

            // Associer les ingrédients sélectionnés au plat
            foreach ($ingredients as $ingredient) {
                $plat->addIngredient($ingredient);
            }

            // Persister le plat (les ingrédients sont déjà dans la base, on ne les persiste pas)
            $entityManager->persist($plat);
            $entityManager->flush();

            // Retourner une réponse avec les informations du plat et des ingrédients
            return $this->json([
                'id' => $plat->getId(),
                'name' => $plat->getName(),
                'cooking_time' => $plat->getCookingTime(),
                'prix' => $plat->getPrix(),
                'ingredients' => array_map(function($ingredient) {
                    return [
                        'id' => $ingredient->getId(),
                        'name' => $ingredient->getName(),
                        'stock' => $ingredient->getStock()
                    ];
                }, $ingredients)
            ], JsonResponse::HTTP_CREATED);
        }
        // Retourner une erreur si des champs nécessaires manquent
        return $this->json(['error' => 'Missing required fields'], JsonResponse::HTTP_BAD_REQUEST);
    }


    // READ - Obtenir un plat par son ID
    #[Route('/{id}', name: 'plat_show', methods: ['GET'])]
    public function show(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $plat = $entityManager->getRepository(Plat::class)->find($id);

        if (!$plat) {
            return $this->json(['error' => 'Plat not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $ingredients = $plat->getIngredients()->toArray(); 

        return $this->json([
            'id' => $plat->getId(),
            'name' => $plat->getName(),
            'cooking_time' => $plat->getCookingTime(),
            'prix' => $plat->getPrix(),
            'ingredients' => array_map(function ($ingredient) {
                return [
                    'id' => $ingredient->getId(),
                    'name' => $ingredient->getName(),
                    'stock' => $ingredient->getStock(),
                ];
            }, $ingredients)
        ]);
    }


    // UPDATE - Mettre à jour un plat
    #[Route('/{id}/edit', name: 'plat_edit', methods: ['PUT'])]
    public function edit(int $id, Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $plat = $entityManager->getRepository(Plat::class)->find($id);

        if (!$plat) {
            return $this->json(['error' => 'Plat not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['name'])) {
            $plat->setName($data['name']);
        }
        if (isset($data['cooking_time'])) {
            $plat->setCookingTime($data['cooking_time']);
        }
        if (isset($data['prix'])) {
            $plat->setPrix($data['prix']);
        }
     

        if (isset($data['ingredients'])) {
            $ingredientRepository = $entityManager->getRepository(Ingredient::class);
            $ingredients = $ingredientRepository->findBy(['id' => $data['ingredients']]);

            if (count($ingredients) !== count($data['ingredients'])) {
                return $this->json(['error' => 'Un ou plusieurs ingrédients n\'existent pas'], JsonResponse::HTTP_BAD_REQUEST);
            }

            foreach ($plat->getIngredients() as $ingredient) {
                $plat->removeIngredient($ingredient);  
            }

            foreach ($ingredients as $ingredient) {
                $plat->addIngredient($ingredient);
            }
        }

        $entityManager->flush();

        return $this->json([
            'id' => $plat->getId(),
            'name' => $plat->getName(),
            'cooking_time' => $plat->getCookingTime(),
            'prix' => $plat->getPrix(),
            'ingredients' => array_map(function ($ingredient) {
                return [
                    'id' => $ingredient->getId(),
                    'name' => $ingredient->getName(),
                    'stock' => $ingredient->getStock(),
                ];
            }, $plat->getIngredients()->toArray())  // Utilisez toArray ici
        ]);
    }

    // DELETE - Supprimer un plat
    #[Route('/{id}/delete', name: 'plat_delete', methods: ['DELETE'])]
    public function delete(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $plat = $entityManager->getRepository(Plat::class)->find($id);

        if (!$plat) {
            return $this->json(['error' => 'Plat not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        // Supprimer le plat
        $entityManager->remove($plat);
        $entityManager->flush();

        return $this->json(['message' => 'Plat deleted successfully']);
    }

}

