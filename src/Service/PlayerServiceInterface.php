<?php
namespace App\Service;
use App\Entity\Player;
interface PlayerServiceInterface
{
    # Creates the player
    public function create();

    # Deletes the player
    public function delete(string $identifier);

    # Retrieves all players
    public function findAll(): array;

    # Retrieve a player
    public function findOneByIdentifier(string $identifier): Player;
}