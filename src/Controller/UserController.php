<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
            'roles' => $user->getRoles(),
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
            'roles' => $user->getRoles(),
        ]);
    }

    #[Route('/update', name: 'api_user_update', methods: ['PUT'])]
    #[IsGranted('ROLE_USER')]
    public function updateUserProfile(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            return new JsonResponse(['message' => 'Utilisateur non connecté.'], Response::HTTP_UNAUTHORIZED);
        }

        $data = json_decode($request->getContent(), true);

        if (!$data) {
            return new JsonResponse(['message' => 'Données invalides.'], Response::HTTP_BAD_REQUEST);
        }

        // Mise à jour des champs si présents
        if (isset($data['phone'])) {
            $user->setPhone($data['phone']);
        }

        if (isset($data['poids'])) {
            $user->setPoids($data['poids']);
        }

        if (isset($data['taille'])) {
            $user->setTaille($data['taille']);
        }

        if (isset($data['sexe'])) {
            $user->setSexe($data['sexe']);
        }

        $em->persist($user);
        $em->flush();

        return new JsonResponse([
            'id' => $user->getId(),
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'phone' => $user->getPhone(),
            'poids' => $user->getPoids(),
            'taille' => $user->getTaille(),
            'sexe' => $user->getSexe(),
            'roles' => $user->getRoles(),
        ]);
    }
}
