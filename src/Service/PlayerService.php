<?php
namespace App\Service;
use DateTime;
use App\Entity\Player;
use App\Form\PlayerType;
use LogicException;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use App\Repository\PlayerRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

class PlayerService implements PlayerServiceInterface
{
    public function __construct(
        private FormFactoryInterface $formFactory,
        private ValidatorInterface $validator,
        private EntityManagerInterface $entityManager,
        private PlayerRepository $playerRepository
    ) {}

    public function findAll(): array
    {
        return $this->entityManager->getRepository(Player::class)->findAll();
    }

    public function serializeJson($object)
    {
        $encoders = new JsonEncoder();
        $defaultContext = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getIdentifier(); // Ce qu'il doit retourner
            },
        ];
        $normalizers = new ObjectNormalizer(null, null, null, null, null, null, $defaultContext);
        $serializer = new Serializer([new DateTimeNormalizer(), $normalizers], [$encoders]);
        return $serializer->serialize($object, 'json');
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
            ->setIdentifier(hash('sha1', uniqid()))
        ;
        $this->submit($player, PlayerType::class, $data);
        $this->isEntityFilled($player);
        $this->entityManager->persist($player);
        $this->entityManager->flush();
        return $player;
    }

    public function submit(Player $player, $formName, $data)
    {
        $dataArray = is_array($data) ? $data : json_decode($data, true);
        if (null !== $data && !is_array($dataArray)) {
            throw new UnprocessableEntityHttpException('Submitted data is not an array -> ' . $data);
        }
        $form = $this->formFactory->create($formName, $player, ['csrf_protection' => false]);
        $form->submit($dataArray, false);
        $errors = $form->getErrors();
        foreach ($errors as $error) {
            $errorMsg  = 'Error ' . get_class($error->getCause());
            $errorMsg .= ' --> ' . $error->getMessageTemplate();
            $errorMsg .= ' ' . json_encode($error->getMessageParameters());
            throw new LogicException($errorMsg);
        }
    }

    public function isEntityFilled(Player $player)
    {
        $player->setIdentifier('badidentifier');
        $errors = $this->validator->validate($player);
        if (count($errors) > 0) {
           $errorMsg  = (string) $errors . 'Wrong data for Entity -> ';
           $errorMsg .= json_encode($player->toArray());
            throw new UnprocessableEntityHttpException($errorMsg);
        }
    }

    public function modify(Player $player, string $data): Player
    {
        $this->submit($player, PlayerType::class, $data);
        $this->isEntityFilled($player);
        $player
            ->setModified(new \DateTime())
        ;
        $this->em->persist($player);
        $this->em->flush();
        return $player;
    }
}
