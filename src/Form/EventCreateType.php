<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\Category;

use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class EventCreateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'min' => 5, 
                        'minMessage' => 'Le champ titre doit faire {{ limit }} minimum'
                    ])
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Sujet'
            ])
            ->add('address', TextType::class, [
                'label' => 'Adresse',
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'min' => 5,
                        'minMessage' => 'Le champ adresse doit faire {{ limit }} minimum'
                    ])
                ]
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville',
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'min' => 5,
                        'minMessage' => 'Le champ ville doit faire {{ limit }} minimum'
                    ])
                ]
            ])
            ->add('postalCode', TextType::class, [
                'label' => 'Code Postal',
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'min' => 5,
                        'minMessage' => 'Le champ code postal doit faire {{ limit }} minimum'
                    ])
                ]
            ])
            ->add('startAt', DateTimeType::class, [
                'label' => 'Date de début',
                'input' => 'datetime_immutable',
                'placeholder' => [
                    'year' => 'Année',
                    'month' => 'Mois',
                    'day' => 'Jour'
                ],
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('category', EntityType::class, [
                'label' => "Catégorie",
                'class' => Category::class,
                'choice_label' => 'name'
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Publier',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
