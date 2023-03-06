<?php
namespace App\Service;
use App\Entity\Player;
interface PlayerServiceInterface
{
    # Creates the player
    public function create();

    public function isEntityFilled(Player $player);

    public function serializeJson($object);

    public function submit(Player $player, $formName, $data);

    public function modify(Player $player, string $data);

    # Deletes the player
    public function delete(string $identifier);

    # Retrieves all players
    public function findAll(): array;

    # Retrieve a player
    public function findOneByIdentifier(string $identifier): Player;
}