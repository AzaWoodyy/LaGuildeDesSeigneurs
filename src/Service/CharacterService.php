<?php
namespace App\Service;
use DateTime;
use App\Entity\Character;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\CharacterType;
use LogicException;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use App\Repository\CharacterRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

class CharacterService implements CharacterServiceInterface
{
    public function __construct(
        private FormFactoryInterface $formFactory,
        private EntityManagerInterface $entityManager,
        private ValidatorInterface $validator,
        private CharacterRepository $characterRepository
    ) {}

    public function serializeJson($object)
    {
        $encoders = new JsonEncoder();
        $defaultContext = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getIdentifier();
            },
        ];
        $normalizers = new ObjectNormalizer(null, null, null, null, null, null, $defaultContext);
        $serializer = new Serializer([new DateTimeNormalizer(), $normalizers], [$encoders]);
        return $serializer->serialize($object, 'json');
    }

    public function findOneByIdentifier(string $identifier): Character
    {
        return $this->entityManager->getRepository(Character::class)->findOneBy(['identifier' => $identifier]);
    }

    public function create(string $data): Character
    {
        $character = new Character();
        $character
            ->setIdentifier(hash('sha1', uniqid()))
            ->setModified(new \DateTime())
            ->setCreated(new \DateTime());
        $this->submit($character, CharacterType::class, $data);
        $this->isEntityFilled($character);
        $this->entityManager->persist($character);
        $this->entityManager->flush();
        return $character;
    }

    public function submit(Character $character, $formName, $data)
    {
        $dataArray = is_array($data) ? $data : json_decode($data, true);
        if (null !== $data && !is_array($dataArray)) {
            throw new UnprocessableEntityHttpException('Submitted data is not an array -> ' . $data);
        }
        $form = $this->formFactory->create($formName, $character, ['csrf_protection' => false]);
        $form->submit($dataArray, false);
        $errors = $form->getErrors();
        foreach ($errors as $error) {
            $errorMsg  = 'Error ' . get_class($error->getCause());
            $errorMsg .= ' --> ' . $error->getMessageTemplate();
            $errorMsg .= ' ' . json_encode($error->getMessageParameters());
            throw new LogicException($errorMsg);
        }
    }

    public function isEntityFilled(Character $character)
    {
        $character->setIdentifier('badidentifier');
        $errors = $this->validator->validate($character);
        if (count($errors) > 0) {
           $errorMsg  = (string) $errors . 'Wrong data for Entity -> ';
           $errorMsg .= json_encode($character->toArray());
            throw new UnprocessableEntityHttpException($errorMsg);
        }
    }

    public function modify(Character $character, string $data): Character
    {
        $this->submit($character, CharacterType::class, $data);
        $this->isEntityFilled($character);
        $character
            ->setModified(new \DateTime())
        ;
        $this->em->persist($character);
        $this->em->flush();
        return $character;
    }
}
