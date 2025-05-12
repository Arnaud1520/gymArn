<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    #[Route('/api/register', name: 'api_register', methods: ['POST'])]
    public function register(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        if (!$data) {
            return new JsonResponse(['message' => 'Données invalides.'], 400);
        }

        // Vérification des champs obligatoires
        $requiredFields = ['name', 'email', 'password', 'sexe'];
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                return new JsonResponse(['message' => "Le champ '$field' est obligatoire."], 400);
            }
        }

        // Vérifier si l'email est déjà utilisé
        $existingUser = $entityManager->getRepository(User::class)->findOneBy(['email' => $data['email']]);
        if ($existingUser) {
            return new JsonResponse(['message' => 'Cet email est déjà utilisé.'], 400);
        }

        // Créer un nouvel utilisateur
        $user = new User();
        $user->setName($data['name']);
        $user->setEmail($data['email']);
        $user->setSexe($data['sexe']);
        
        // Vérification des champs optionnels
        if (!empty($data['phone'])) {
            $user->setPhone($data['phone']);
        }
        if (!empty($data['poids'])) {
            $user->setPoids((float) $data['poids']);
        }
        if (!empty($data['taille'])) {
            $user->setTaille((float) $data['taille']);
        }

        // Hashage du mot de passe
        $user->setPassword($passwordHasher->hashPassword($user, $data['password']));

        // Sauvegarde dans la base de données
        $entityManager->persist($user);
        $entityManager->flush();

        return new JsonResponse([
            'message' => 'Inscription réussie.',
            'user' => [
                'id' => $user->getId(),
                'name' => $user->getName(),
                'email' => $user->getEmail(),
            ]
        ], 201);
    }
}
