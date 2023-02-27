<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\CharacterServiceInterface;
use App\Entity\Character;

class CharacterController extends AbstractController
{
    public function __construct(
        private CharacterServiceInterface $characterService
    ) {}

    #[Route('/character/display/{identifier}', name: 'app_character_display', methods: ['GET', 'HEAD'])]
    public function display($identifier): JsonResponse
    {
        $character = $this->characterService->findOneByIdentifier($identifier);
        return new JsonResponse($character->toArray());
    }

    #[Route('/character/create', name: 'app_character_create', methods: ['POST','HEAD'])]
    public function create(): JsonResponse
    {
        $character = $this->characterService->create();
        return new JsonResponse($character->toArray(), JsonResponse::HTTP_CREATED);
    }

}
