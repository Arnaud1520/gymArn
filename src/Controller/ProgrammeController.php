<?php

namespace App\Controller;

use App\Entity\Programme;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/programmes')]
class ProgrammeController extends AbstractController
{
    #[Route('', name: 'programme_index', methods: ['GET'])]
    public function index(EntityManagerInterface $em, SerializerInterface $serializer): JsonResponse
    {
        $programmes = $em->getRepository(Programme::class)->findAll();
        $json = $serializer->serialize($programmes, 'json', ['groups' => 'programme:read']);

        return new JsonResponse($json, 200, [], true);
    }

    #[Route('/{id}', name: 'programme_show', methods: ['GET'])]
    public function show(Programme $programme, SerializerInterface $serializer): JsonResponse
    {
        $json = $serializer->serialize($programme, 'json', ['groups' => 'programme:read']);
        return new JsonResponse($json, 200, [], true);
    }

    #[Route('', name: 'programme_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em, SerializerInterface $serializer): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $programme = new Programme();
        $programme->setName($data['name'] ?? '');
        $programme->setUser($this->getUser());

        $em->persist($programme);
        $em->flush();

        $json = $serializer->serialize($programme, 'json', ['groups' => 'programme:read']);
        return new JsonResponse($json, 201, [], true);
    }

    #[Route('/{id}', name: 'programme_update', methods: ['PUT'])]
    public function update(Request $request, Programme $programme, EntityManagerInterface $em, SerializerInterface $serializer): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (isset($data['name'])) {
            $programme->setName($data['name']);
        }

        $em->flush();

        $json = $serializer->serialize($programme, 'json', ['groups' => 'programme:read']);
        return new JsonResponse($json, 200, [], true);
    }

    #[Route('/{id}', name: 'programme_delete', methods: ['DELETE'])]
    public function delete(Programme $programme, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($programme);
        $em->flush();

        return new JsonResponse(null, 204);
    }
}
