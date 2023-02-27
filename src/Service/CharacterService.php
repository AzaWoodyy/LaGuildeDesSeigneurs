<?php
namespace App\Service;
use DateTime;
use App\Entity\Character;
use Doctrine\ORM\EntityManagerInterface;
class CharacterService implements CharacterServiceInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    public function findOneByIdentifier(string $identifier): Character
    {
        return $this->entityManager->getRepository(Character::class)->findOneBy(['identifier' => $identifier]);
    }

    public function create(): Character
    {
        $character = new Character();
        $character
            ->setKind('Dame')
            ->setName('Anardil')
            ->setSurname('Amie du Soleil')
            ->setCaste('Magicien')
            ->setKnowledge('Sciences')
            ->setIntelligence(130)
            ->setLife(11)
            ->setIdentifier(hash('sha1', uniqid()))
            ->setImage('img/characters/Anardil.jpg')
            ->setCreated(new \DateTime())
        ;
        $this->entityManager->persist($character);
        $this->entityManager->flush();
        return $character;
    }
}
