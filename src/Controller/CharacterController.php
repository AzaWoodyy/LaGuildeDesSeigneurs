<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Character;

class CharacterController extends AbstractController
{
    #[Route('/character/display', name: 'app_character_display')]
    public function display(): JsonResponse
    {
        $character = new Character();
        return new JsonResponse($character->toArray());
    }
}
