<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\PlayerServiceInterface;
use App\Entity\Player;

class PlayerController extends AbstractController
{
    public function __construct(
        private PlayerServiceInterface $playerService
    ) {}

    #[Route('/player/display', name: 'app_player_display_all', methods: ['GET', 'HEAD'])]
    public function displayAll(): JsonResponse
    {
        $players = $this->playerService->findAll();
        return new JsonResponse($players);
    }

    #[Route('/player/display/{identifier}', name: 'app_player_display', methods: ['GET', 'HEAD'])]
    public function display($identifier): JsonResponse
    {
        $player = $this->playerService->findOneByIdentifier($identifier);
        return new JsonResponse($player->toArray());
    }

    #[Route('/player/create', name: 'app_player_create', methods: ['POST','HEAD'])]
    public function create(): JsonResponse
    {
        $player = $this->playerService->create();
        return new JsonResponse($player->toArray(), JsonResponse::HTTP_CREATED);
    }

    #[Route('/player/delete', name: 'app_player_create', methods: ['POST','HEAD'])]
    public function delete(string $identifier): JsonResponse
    {
        $player = $this->playerService->delete($identifier);
        return new JsonResponse($player->toArray());
    }

}
