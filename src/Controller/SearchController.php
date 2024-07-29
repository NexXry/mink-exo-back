<?php

namespace App\Controller;

use App\Repository\AnimalRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

class SearchController extends AbstractController
{
    #[Route('/api/search', name: 'app_search')]
    public function index(Request $request,AnimalRepository $animalRepository): JsonResponse
    {
        $query = $request->query->get('q');

        if (!$query) {
            return $this->json(['error' => 'No query found'], Response::HTTP_BAD_REQUEST);
        }

        $animals = $animalRepository->findSearchAnimal($query);

        return $this->json($animals, Response::HTTP_OK, context: ['groups' => 'animal_read']);
    }
}
