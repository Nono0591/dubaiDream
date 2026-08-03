<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints as assert;

class ResetPasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
           ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'constraints' => [new assert\Length(['min' => 6, 'max' => 30])],
                'first_options' => [
                'label' => 'Votre nouveau mot de passe',
                'attr' => [
                'placeholder' => 'Veuillez saisir votre nouveau mot de passe',
                 ],
                 'hash_property_path' => 'password',

                ],
                'second_options' => [
                'label' => 'Répéter le nouveau mot de passe',
                'attr' => [
                'placeholder' => 'Veuillez répéter votre nouveau mot de passe',
                ]
                ],
                  'mapped' => false,
            ])

                ->add('submit', SubmitType::class,[
                'label' => "Modifier mon mot de passe",
                'attr' => [
                    'class' => 'btn btn-success mt-3'
                ]
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
