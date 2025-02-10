<?php

namespace App\Controller;

use App\Entity\Paiement;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpClient\HttpClient;
use Doctrine\ORM\EntityManagerInterface;

class PaiementController extends AbstractController
{
    private $firebaseDatabaseUrl = 'https://mobile-restaurant-10c40-default-rtdb.firebaseio.com/'; 
    private $firebaseSecret = 'AIzaSyDvXapJGj1GsNwsqdOL-DAqrJRVRHfy4BQ
    '; 
    public function __construct(private EntityManagerInterface $entityManager) {}

#[Route('/api/paiements', name: 'api_paiements')]
public function getPaiements(): JsonResponse
{
    $client = HttpClient::create();

    try {
        $response = $client->request('GET', $this->firebaseDatabaseUrl . '/paiement.json', [
            'query' => ['auth' => $this->firebaseSecret]
        ]);
        $data = $response->toArray();
        
        dump($data);

        $paiements = [];
        $totalSales = 0;

        foreach ($data as $payment) {
            $paiementEntity = new Paiement();
            $paiementEntity->setDate($payment['date'] ?? '');
            $paiementEntity->setPlat($payment['plat'] ?? '');
            $paiementEntity->setQuantity($payment['quantity'] ?? 0);
            $paiementEntity->setStatus($payment['status'] ?? '');
            $paiementEntity->setTotalPrice($payment['totalPrice'] ?? 0);
            $paiementEntity->setUserEmail($payment['userEmail'] ?? '');

            $this->entityManager->persist($paiementEntity);

            $paiements[] = [
                'date' => $payment['date'] ?? '',
                'plat' => $payment['plat'] ?? '',
                'quantity' => $payment['quantity'] ?? 0,
                'status' => $payment['status'] ?? '',
                'totalPrice' => $payment['totalPrice'] ?? 0,
                'userEmail' => $payment['userEmail'] ?? '',
            ];

            $totalSales += $payment['totalPrice'] ?? 0;
        }

        $this->entityManager->flush();

        return new JsonResponse(['totalSales' => $totalSales, 'paiements' => $paiements]);
    } catch (\Exception $e) {
        return new JsonResponse(['error' => $e->getMessage()], 500);
    }
}

#[Route('/api/commandes', name: 'api_commande_en_cours')]
    public function getPaiementsEnCours(): JsonResponse
    {
        $client = HttpClient::create();

        try {
            $response = $client->request('GET', $this->firebaseDatabaseUrl . '/commande.json', [
                'query' => ['auth' => $this->firebaseSecret]
            ]);

            $data = $response->toArray();

            $commandeEnCours = [];

            foreach ($data as $commande) {
                if ($commande['status'] === 'en cours') {
                    $commandeEnCours[] = [
                        'date' => $commande['date'] ?? '',
                        'plat' => $commande['plat'] ?? '',
                        'quantite' => $commande['quantite'] ?? 0,
                        'status' => $commande['status'] ?? '',
                        'prix_total' => $commande['prix_total'] ?? 0,
                        'client_email' => $commande['client_email'] ?? '',
                    ];
                }
            }

            return new JsonResponse(['commande' => $commandeEnCours]);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }
}
