<?php
namespace App\Service;
use DateTime;
use App\Entity\Player;
use Doctrine\ORM\EntityManagerInterface;
class PlayerService implements PlayerServiceInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    public function findAll(): array
    {
        return $this->entityManager->getRepository(Player::class)->findAll();
    }

    public function delete(string $identifier): void
    {
        $player = $this->entityManager->getRepository(Player::class)->findOneBy(['identifier' => $identifier]);
        $this->entityManager->remove($player);
        $this->entityManager->flush();
    }

    public function findOneByIdentifier(string $identifier): Player
    {
        return $this->entityManager->getRepository(Player::class)->findOneBy(['identifier' => $identifier]);
    }

    public function create(): Player
    {
        $player = new Player();
        $player
            ->setFirstname('John')
            ->setLastname('Doe')
            ->setEmail('test@test.com')
            ->setMirian(1000)
            ->setIdentifier(hash('sha1', uniqid()))
        ;
        $this->entityManager->persist($player);
        $this->entityManager->flush();
        return $player;
    }
}
