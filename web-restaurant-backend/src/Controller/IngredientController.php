<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Form\IngredientType;
use App\Repository\IngredientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/ingredient')]
class IngredientController extends AbstractController
{
    // Lister tous les ingrédients
    #[Route('/', name: 'ingredient_index', methods: ['GET'])]
    public function index(IngredientRepository $ingredientRepository): JsonResponse
    {
        $ingredients = $ingredientRepository->findAll();
        
        $ingredientData = [];
        foreach ($ingredients as $ingredient) {
            $ingredientData[] = [
                'id' => $ingredient->getId(),
                'name' => $ingredient->getName(),
                'stock' => $ingredient->getStock(),
            ];
        }

        return $this->json($ingredientData);
    }

    // Créer un nouvel ingrédient
#[Route('/new', name: 'ingredient_new', methods: ['POST'])]
public function new(Request $request, EntityManagerInterface $entityManager): JsonResponse
{
    // Récupérer le contenu brut de la requête
    $data = json_decode($request->getContent(), true);

    // Vérifiez si les données sont valides
    if (isset($data['name']) && isset($data['stock'])) {
        $ingredient = new Ingredient();
        
        // Assigner les données à l'entité
        $ingredient->setName($data['name']);
        $ingredient->setStock($data['stock']);

        // Persister et flush
        $entityManager->persist($ingredient);
        $entityManager->flush();

        return $this->json([
            'id' => $ingredient->getId(),
            'name' => $ingredient->getName(),
            'stock' => $ingredient->getStock(),
        ], JsonResponse::HTTP_CREATED);
    } else {
        return $this->json([
            'error' => 'Missing name or stock',
        ], JsonResponse::HTTP_BAD_REQUEST);
    }
}

// READ - Obtenir un ingrédient par son ID
#[Route('/{id}', name: 'ingredient_show', methods: ['GET'])]
public function show(int $id ,  EntityManagerInterface $entityManager): JsonResponse
{
    $ingredient = $entityManager->getRepository(Ingredient::class)->find($id);

    if (!$ingredient) {
        return $this->json(['error' => 'Ingredient not found'], JsonResponse::HTTP_NOT_FOUND);
    }

    return $this->json([
        'id' => $ingredient->getId(),
        'name' => $ingredient->getName(),
        'stock' => $ingredient->getStock()
    ]);
}
    // Modifier un ingrédient
    #[Route('/{id}/edit', name: 'ingredient_edit', methods: ['PUT'])]
    public function edit(Request $request, Ingredient $ingredient, EntityManagerInterface $entityManager): JsonResponse
{
    // Récupérer le contenu brut de la requête
    $data = json_decode($request->getContent(), true);

    // Vérifier que les données nécessaires sont présentes
    if (isset($data['name']) && isset($data['stock'])) {
        // Modifier les propriétés de l'ingrédient
        $ingredient->setName($data['name']);
        $ingredient->setStock($data['stock']);

        // Flusher les changements
        $entityManager->flush();

        return $this->json([
            'id' => $ingredient->getId(),
            'name' => $ingredient->getName(),
            'stock' => $ingredient->getStock(),
        ]);
    } else {
        return $this->json([
            'error' => 'Missing name or stock',
        ], JsonResponse::HTTP_BAD_REQUEST);
    }
}


    // Supprimer un ingrédient
    #[Route('/{id}/delete', name: 'ingredient_delete', methods: ['DELETE'])]
    public function delete(Ingredient $ingredient, EntityManagerInterface $entityManager): JsonResponse
    {
        $entityManager->remove($ingredient);
        $entityManager->flush();

        return $this->json(['status' => 'Ingredient deleted successfully']);
    }
}
