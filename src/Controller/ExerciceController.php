<?php

namespace App\Controller;

use App\Entity\Exercice;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/api/exercices')]
class ExerciceController extends AbstractController
{
    private $uploadDir = 'uploads/exercices';

    #[Route('', name: 'exercice_index', methods: ['GET'])]
    public function index(EntityManagerInterface $em, SerializerInterface $serializer): JsonResponse
    {
        $exercices = $em->getRepository(Exercice::class)->findAll();
        $json = $serializer->serialize($exercices, 'json', ['groups' => 'programme:read']);

        return new JsonResponse($json, 200, [], true);
    }

    #[Route('/{id}', name: 'exercice_show', methods: ['GET'])]
    public function show(Exercice $exercice, SerializerInterface $serializer): JsonResponse
    {
        $json = $serializer->serialize($exercice, 'json', ['groups' => 'programme:read']);
        return new JsonResponse($json, 200, [], true);
    }

    #[Route('', name: 'exercice_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em, SluggerInterface $slugger, SerializerInterface $serializer): JsonResponse
    {
        $name = $request->get('name');
        $categorie = $request->get('categorie');
        $description = $request->get('description');
        $imageFile = $request->files->get('imageFile');

        if (!$name || !$categorie || !$description) {
            return new JsonResponse(['error' => 'Champs requis manquants'], Response::HTTP_BAD_REQUEST);
        }

        $exercice = new Exercice();
        $exercice->setName($name);
        $exercice->setCategorie($categorie);
        $exercice->setDescription($description);

        if ($imageFile) {
            $newFilename = $this->uploadImage($imageFile, $slugger);
            $exercice->setImage($newFilename); // champ string dans l'entité (nom fichier)
        }

        $em->persist($exercice);
        $em->flush();

        $json = $serializer->serialize($exercice, 'json', ['groups' => 'programme:read']);
        return new JsonResponse($json, 201, [], true);
    }

    #[Route('/{id}', name: 'exercice_update', methods: ['PATCH', 'PUT'])]
    public function update(Request $request, Exercice $exercice, EntityManagerInterface $em, SluggerInterface $slugger): JsonResponse
    {
        $data = $request->request->all();
        $imageFile = $request->files->get('imageFile');

        if (array_key_exists('name', $data)) {
            $exercice->setName($data['name']);
        }

        if (array_key_exists('categorie', $data)) {
            $exercice->setCategorie($data['categorie']);
        }

        if (array_key_exists('description', $data)) {
            $exercice->setDescription($data['description']);
        }

        if ($imageFile) {
            $newFilename = $this->uploadImage($imageFile, $slugger);
            $exercice->setImage($newFilename);
        }

        $exercice->setUpdatedAt(new \DateTimeImmutable());

        $em->flush();

        return new JsonResponse(['message' => 'Exercice modifié avec succès']);
    }

    #[Route('/{id}', name: 'exercice_delete', methods: ['DELETE'])]
    public function delete(Exercice $exercice, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($exercice);
        $em->flush();

        return new JsonResponse(null, 204);
    }

    private function uploadImage($imageFile, SluggerInterface $slugger): string
    {
        $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $slugger->slug($originalFilename);
        $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

        $imageFile->move($this->getParameter('kernel.project_dir') . '/public/' . $this->uploadDir, $newFilename);

        return $newFilename;
    }

    #[Route('/{id}/upload-image', name: 'exercice_upload_image', methods: ['POST'])]
public function uploadImageOnly(Request $request, Exercice $exercice, EntityManagerInterface $em, SluggerInterface $slugger): JsonResponse
{
    $imageFile = $request->files->get('imageFile');

    if (!$imageFile) {
        return new JsonResponse(['error' => 'Aucun fichier fourni'], Response::HTTP_BAD_REQUEST);
    }

    $newFilename = $this->uploadImage($imageFile, $slugger);
    $exercice->setImage($newFilename);
    $exercice->setUpdatedAt(new \DateTimeImmutable());

    $em->flush();

    return new JsonResponse(['message' => 'Image uploadée avec succès']);
}
}
