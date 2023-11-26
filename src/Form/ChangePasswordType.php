<?php

namespace App\Form;

use App\Entity\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('plainPassword', PasswordType::class, [
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'label' => 'Nouveau mot de passe',
                'constraints' => [
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Le champ nouveau mot de passe doit faire {{ limit }} caractères minimum',
                    ]),
                    new NotBlank([
                        'message' => 'Le champ nouveau mot de passe est obligatoire',
                    ]),
                ]
                ])
            ->add('passwordConfirm', PasswordType::class, [
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'label' => 'Confirmation nouveau mot de passe',
                'constraints' => [
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Le champ confirmation mot de passe doit faire {{ limit }} caractères minimum',
                    ]),
                    new NotBlank([
                        'message' => 'Le champ confirmation mot de passe est obligatoire',
                    ]),
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
