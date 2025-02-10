<?php
namespace App\Controller;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class FirebaseUserController
{
    private Auth $auth;

    public function __construct()
    {
        $firebaseConfig = [
            'type' => $_ENV['FIREBASE_TYPE'],
            'project_id' => $_ENV['FIREBASE_PROJECT_ID'],
            'private_key_id' => $_ENV['FIREBASE_PRIVATE_KEY_ID'],
            'private_key' => $_ENV['FIREBASE_PRIVATE_KEY'],
            'client_email' => $_ENV['FIREBASE_CLIENT_EMAIL'],
            'client_id' => $_ENV['FIREBASE_CLIENT_ID'],
            'auth_uri' => $_ENV['FIREBASE_AUTH_URI'],
            'token_uri' => $_ENV['FIREBASE_TOKEN_URI'],
            'auth_provider_x509_cert_url' => $_ENV['FIREBASE_AUTH_PROVIDER_X509_CERT_URL'],
            'client_x509_cert_url' => $_ENV['FIREBASE_CLIENT_X509_CERT_URL'],
            'universe_domain' => $_ENV['FIREBASE_UNIVERSE_DOMAIN'],
        ];

        $factory = (new Factory)
            ->withServiceAccount($firebaseConfig);

        $this->auth = $factory->createAuth();
    }

    #[Route('/api/users', name: 'api_users')]
    public function listUsers(): JsonResponse
    {
        try {
            $users = [];
            foreach ($this->auth->listUsers() as $user) {
                $users[] = [
                    'uid' => $user->uid,
                    'email' => $user->email,
                    'displayName' => $user->displayName,
                ];
            }

            return new JsonResponse(['users' => $users]);
        } catch (\Throwable $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }
}