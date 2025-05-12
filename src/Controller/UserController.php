<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/user')]
final class UserController extends AbstractController
{
    #[Route('/me', name: 'api_user_me', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function getCurrentUser(): JsonResponse
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            return new JsonResponse(['message' => 'Utilisateur non connecté.'], 401);
        }

        return new JsonResponse([
            'id' => $user->getId(),
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'phone' => $user->getPhone(),
            'poids' => $user->getPoids(),
            'taille' => $user->getTaille(),
            'sexe' => $user->getSexe(),
        ]);
    }

    #[Route('/{id}', name: 'api_user_show', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function getUserById(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $user = $entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            return new JsonResponse(['message' => 'Utilisateur non trouvé.'], 404);
        }

        return new JsonResponse([
            'id' => $user->getId(),
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'phone' => $user->getPhone(),
            'poids' => $user->getPoids(),
            'taille' => $user->getTaille(),
            'sexe' => $user->getSexe(),
        ]);
    }
}
