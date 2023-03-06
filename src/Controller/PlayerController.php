<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\PlayerServiceInterface;
use App\Entity\Player;
use Symfony\Component\HttpFoundation\Request;

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
    public function create(Request $request): JsonResponse
    {
        $player = $this->playerService->create($request->getContent());
        return new JsonResponse($player->toArray(), JsonResponse::HTTP_CREATED);
    }

    #[Route('/player/update', name: 'app_player_update', methods: ['PUT','HEAD'])]
    public function modify(Request $request, Player $player): JsonResponse
    {
        $player = $this->playerService->modify($player, $request->getContent());
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }

    #[Route('/player/delete', name: 'app_player_create', methods: ['POST','HEAD'])]
    public function delete(string $identifier): JsonResponse
    {
        $player = $this->playerService->delete($identifier);
        return new JsonResponse($player->toArray());
    }

}
