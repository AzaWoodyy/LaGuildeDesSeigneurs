<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\CharacterServiceInterface;
use App\Entity\Character;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;


class CharacterController extends AbstractController
{
    public function __construct(
        private CharacterServiceInterface $characterService
    ) {}

    #[Route('/character/display/{identifier}', name: 'app_character_display', methods: ['GET', 'HEAD'])]
    #[Entity('character', expr: 'repository.findOneByIdentifier(identifier)')]
    public function display($identifier): JsonResponse
    {
        $character = $this->characterService->findOneByIdentifier($identifier);
        return JsonResponse::fromJsonString($this->characterService->serializeJson($character));
    }

    #[Route('/character/create', name: 'app_character_create', methods: ['POST','HEAD'])]
    public function create(Request $request): JsonResponse
    {
        $character = $this->characterService->create($request->getContent());
        return new JsonResponse($character->toArray(), JsonResponse::HTTP_CREATED);
    }

    #[Route('/character/update', name: 'app_character_update', methods: ['PUT','HEAD'])]
    public function modify(Request $request, Character $character): JsonResponse
    {
        $character = $this->characterService->modify($character, $request->getContent());
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }

}
