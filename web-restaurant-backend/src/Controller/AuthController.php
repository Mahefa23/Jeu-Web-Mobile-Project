<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request; 
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

#[Route('/api/auth')]
class AuthController extends AbstractController
{
    private $userRepository;
    private $passwordHasher;

    public function __construct(UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher)
    {
        $this->userRepository = $userRepository;
        $this->passwordHasher = $passwordHasher;
    }

    // Fonction pour se connecter
    #[Route('/login', name: 'user_login', methods: ['POST'])]
public function login(
    Request $request, 
    UserRepository $userRepository, 
    UserPasswordHasherInterface $passwordHasher
): JsonResponse {
    $data = json_decode($request->getContent(), true);

    $email = $data['email'] ?? '';
    $password = $data['password'] ?? '';

    $user = $userRepository->findOneBy(['email' => $email]);

    if (!$user || !$passwordHasher->isPasswordValid($user, $password)) {
        return new JsonResponse(['message' => 'Invalid credentials'], 401);
    }

    $redirectUrl = in_array('ROLE_ADMIN', $user->getRoles()) ? '/dashboard' : '/frontoffice';

    return new JsonResponse([
        'message' => 'Login successful',
        'redirect' => $redirectUrl,
        'user' => [
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'roles' => $user->getRoles(),
        ],
    ]);
}

    // Fonction pour s'inscrire
#[Route('/create-user', name: 'create_user', methods: ['POST'])]
public function createUser(
    Request $request,
    EntityManagerInterface $entityManager,
    UserPasswordHasherInterface $passwordHasher
): JsonResponse {
    $data = json_decode($request->getContent(), true);

    // Validation simple
    if (empty($data['email']) || empty($data['password'])) {
        return new JsonResponse(['error' => 'Email and password are required'], 400);
    }

    $user = new User();
    $user->setEmail($data['email']);
    
    $hashedPassword = $passwordHasher->hashPassword($user, $data['password']);
    $user->setPassword($hashedPassword);

    $role = isset($data['role']) && in_array($data['role'], ['ROLE_USER', 'ROLE_ADMIN']) 
        ? $data['role'] 
        : 'ROLE_USER';

    $user->setRoles([$role]);

    $entityManager->persist($user);
    $entityManager->flush();

    return new JsonResponse(['message' => 'User created successfully'], 201);
}
  
    // Fonction pour se déconnecter
    #[Route('/logout', name: 'api_logout', methods: ['POST'])]
    public function logout(SessionInterface $session): JsonResponse
    {
        $session->invalidate();
        return new JsonResponse(['message' => 'Logout successful']);
    }
}
