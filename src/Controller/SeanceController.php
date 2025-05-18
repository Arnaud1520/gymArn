<?php

namespace App\Controller;

use App\Entity\Seance;
use App\Repository\SeanceRepository;
use App\Repository\UserRepository;
use App\Repository\ProgrammeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

final class SeanceController extends AbstractController
{
    private $seanceRepository;
    private $entityManager;
    private $userRepository;
    private $programmeRepository;

    public function __construct(
        SeanceRepository $seanceRepository,
        EntityManagerInterface $entityManager,
        UserRepository $userRepository,
        ProgrammeRepository $programmeRepository
    ) {
        $this->seanceRepository = $seanceRepository;
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->programmeRepository = $programmeRepository;
    }

    #[Route('/api/seances', name: 'get_seances', methods: ['GET'])]
    public function getAllSeances(): JsonResponse
    {
        $seances = $this->seanceRepository->findAll();
        return $this->json($seances, 200, [], ['groups' => 'seance:read']);
    }

    #[Route('/api/seance/{id}', name: 'get_seance', methods: ['GET'])]
    public function getSeance(int $id): JsonResponse
    {
        $seance = $this->seanceRepository->find($id);

        if (!$seance) {
            return $this->json(['message' => 'Séance non trouvée'], 404);
        }

        return $this->json($seance, 200, [], ['groups' => 'seance:read']);
    }

    #[Route('/api/seance', name: 'create_seance', methods: ['POST'])]
    public function createSeance(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // Validation des données
        $user = $this->userRepository->find($data['userId']);
        $programme = $this->programmeRepository->find($data['programmeId']);

        if (!$user) {
            return $this->json(['message' => 'Utilisateur non trouvé'], 404);
        }

        if (!$programme) {
            return $this->json(['message' => 'Programme non trouvé'], 404);
        }

        $seance = new Seance();
        $seance->setDate(new \DateTime($data['date']));
        $seance->setUser($user);
        $seance->setProgramme($programme);

        $this->entityManager->persist($seance);
        $this->entityManager->flush();

        return $this->json($seance, 201, [], ['groups' => 'seance:read']);
    }

    #[Route('/api/seance/{id}', name: 'update_seance', methods: ['PUT'])]
    public function updateSeance(int $id, Request $request): JsonResponse
    {
        $seance = $this->seanceRepository->find($id);

        if (!$seance) {
            return $this->json(['message' => 'Séance non trouvée'], 404);
        }

        $data = json_decode($request->getContent(), true);

        // Validation des données
        $user = $this->userRepository->find($data['userId']);
        $programme = $this->programmeRepository->find($data['programmeId']);

        if (!$user) {
            return $this->json(['message' => 'Utilisateur non trouvé'], 404);
        }

        if (!$programme) {
            return $this->json(['message' => 'Programme non trouvé'], 404);
        }

        $seance->setDate(new \DateTime($data['date']));
        $seance->setUser($user);
        $seance->setProgramme($programme);

        $this->entityManager->flush();

        return $this->json($seance, 200, [], ['groups' => 'seance:read']);
    }

    #[Route('/api/seance/{id}', name: 'delete_seance', methods: ['DELETE'])]
    public function deleteSeance(int $id): JsonResponse
    {
        $seance = $this->seanceRepository->find($id);

        if (!$seance) {
            return $this->json(['message' => 'Séance non trouvée'], 404);
        }

        $this->entityManager->remove($seance);
        $this->entityManager->flush();

        return $this->json(['message' => 'Séance supprimée'], 200);
    }
}
