<?php
namespace App\Service;
use App\Entity\Character;
interface CharacterServiceInterface
{
    # Creates the character
    public function create();

    # Retrieve a character
    public function findOneByIdentifier(string $identifier): Character;
}