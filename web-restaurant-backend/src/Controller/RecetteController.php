<?php

namespace App\Controller;

use App\Entity\Recette;
use App\Entity\Plat;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/recette')]
class RecetteController extends AbstractController
{
    // Lister tous les plats et recettes
#[Route('/', name: 'plat_recette_index', methods: ['GET'])]
public function index(EntityManagerInterface $entityManager): JsonResponse
{
    // Récupérer tous les plats
    $plats = $entityManager->getRepository(Plat::class)->findAll();
    
    $platRecetteData = [];
    foreach ($plats as $plat) {
        // Récupérer les recettes liées à chaque plat
        $recettes = $plat->getRecettes(); // Méthode présumée getRecettes() dans Plat
        
        $recetteData = [];
        foreach ($recettes as $recette) {
            $recetteData[] = [
                'id' => $recette->getId(),
                'recetteText' => $recette->getRecetteText(),
            ];
        }

        // Ajouter les plats et les recettes liées dans le tableau final
        $platRecetteData[] = [
            'id' => $plat->getId(),
            'name' => $plat->getName(),
            'recettes' => $recetteData,
        ];
    }

    return $this->json($platRecetteData);
}
    // CREATE - Ajouter une recette
#[Route('/new', name: 'recette_new', methods: ['POST'])]
public function new(Request $request, EntityManagerInterface $entityManager): JsonResponse
{
    // Récupérer et décoder les données de la requête JSON
    $data = json_decode($request->getContent(), true);
    
    // Debug : vérifier les données reçues
    dump($data); 

    // Vérifier que les deux champs requis sont présents
    if (!isset($data['recetteText']) || !isset($data['platId'])) {
        return $this->json(['error' => 'Missing required fields'], JsonResponse::HTTP_BAD_REQUEST);
    }

    // Trouver le plat correspondant à l'ID
    $plat = $entityManager->getRepository(Plat::class)->find($data['platId']);
    
    if (!$plat) {
        return $this->json(['error' => 'Plat not found'], JsonResponse::HTTP_NOT_FOUND);
    }

    // Créer la nouvelle recette
    $recette = new Recette();
    $recette->setRecetteText($data['recetteText']);
    $recette->setPlat($plat);

    // Sauvegarder dans la base de données
    $entityManager->persist($recette);
    $entityManager->flush();

    // Retourner la réponse avec les informations de la recette créée
    return $this->json([
        'id' => $recette->getId(),
        'recetteText' => $recette->getRecetteText(),
        'plat' => $plat->getName(),
    ], JsonResponse::HTTP_CREATED);
}

    // READ - Obtenir une recette par son ID
    #[Route('/{id}', name: 'recette_show', methods: ['GET'])]
    public function show(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $recette = $entityManager->getRepository(Recette::class)->find($id);

        if (!$recette) {
            return $this->json(['error' => 'Recette not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        return $this->json([
            'id' => $recette->getId(),
            'recetteText' => $recette->getRecetteText(),
            'plat' => $recette->getPlat()->getName(),
        ]);
    }

    // UPDATE - Mettre à jour une recette
    #[Route('/{id}/edit', name: 'recette_edit', methods: ['PUT'])]
    public function edit(int $id, Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $recette = $entityManager->getRepository(Recette::class)->find($id);

        if (!$recette) {
            return $this->json(['error' => 'Recette not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['recetteText'])) {
            $recette->setRecetteText($data['recetteText']);
        }

        $entityManager->flush();

        return $this->json([
            'id' => $recette->getId(),
            'recetteText' => $recette->getRecetteText(),
        ]);
    }

    // DELETE - Supprimer une recette
    #[Route('/{id}/delete', name: 'recette_delete', methods: ['DELETE'])]
    public function delete(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $recette = $entityManager->getRepository(Recette::class)->find($id);

        if (!$recette) {
            return $this->json(['error' => 'Recette not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $entityManager->remove($recette);
        $entityManager->flush();

        return $this->json(['message' => 'Recette deleted successfully']);
    }

    #[Route('/plat/{id}', name: 'recettes_par_plat', methods: ['GET'])]
public function recettesParPlat(int $id, EntityManagerInterface $entityManager): JsonResponse
{
    // Trouver le plat par ID
    $plat = $entityManager->getRepository(Plat::class)->find($id);

    if (!$plat) {
        return $this->json(['error' => 'Plat non trouvé'], JsonResponse::HTTP_NOT_FOUND);
    }

    // Récupérer les recettes associées
    $recettes = $plat->getRecettes();

    $recetteData = [];
    foreach ($recettes as $recette) {
        $recetteData[] = [
            'id' => $recette->getId(),
            'recetteText' => $recette->getRecetteText(),
        ];
    }

    return $this->json([
        'plat' => $plat->getName(),
        'recettes' => $recetteData,
    ]);
}

}
