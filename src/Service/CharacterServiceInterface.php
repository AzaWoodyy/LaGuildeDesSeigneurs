<?php
namespace App\Service;
use App\Entity\Character;
interface CharacterServiceInterface
{
    # Creates the character
    public function create(string $data);

    public function isEntityFilled(Character $character);

    public function submit(Character $character, $formName, $data);

    public function modify(Character $character, string $data);

    # Retrieve a character
    public function findOneByIdentifier(string $identifier): Character;
}