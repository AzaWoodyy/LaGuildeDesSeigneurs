<?php

namespace App\Form;

use App\Entity\Character;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CharacterType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Character::class,
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('surname', TextType::class)
            ->add('caste', TextType::class)
            ->add('knowledge', TextType::class)
            ->add('intelligence', IntegerType::class)
            ->add('life', IntegerType::class)
            ->add('image', TextType::class)
            ->add('kind', TextType::class)
            ->add('created', DateTimeType::class)
            ->add('identifier', TextType::class)
            ->add('modified', DateTimeType::class)
       ;
    }
}
