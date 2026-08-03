<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Validator\Constraints as assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

class InscriptionUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email',EmailType::class,[
                'label' => 'Votre adresse email',
                'attr' => [
                    'placeholder' => 'Veuillez saisir votre adresse email'
                ]
            ])
           
           ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'constraints' => [new assert\Length(['min' => 6, 'max' => 30])],
                'first_options' => [
                'label' => 'Votre mot de passe',
                'attr' => [
                'placeholder' => 'Veuillez saisir votre mot de passe',
                 ],
                 'hash_property_path' => 'password',

                ],
                'second_options' => [
                'label' => 'Répéter le mot de passe',
                'attr' => [
                'placeholder' => 'Veuillez répéter votre mot de passe',
                ]
                ],
                  'mapped' => false,
            ])

            ->add('firstname',TextType::class,[
                'constraints' => [new assert\Length(['min' => 3, 'max' => 30])],
                'label' => 'Votre prénom',
                'attr' => [
                    'placeholder' => 'Veuillez saisir votre prénom'
                ]
            ])
            ->add('lastname',TextType::class,[
                'constraints' => [new assert\Length(['min' => 2, 'max' => 30])],
                'label' => 'Votre nom',
                'attr' => [
                    'placeholder' => 'Veuillez saisir votre nom'
                ]
            ])
            ->add('submit', SubmitType::class,[
                'label' => "S'inscrire",
                'attr' => [
                    'class' => 'btn btn-success mt-3'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'constraints' => [
                new UniqueEntity([
                    'entityClass' => User::class,
                    'fields' => ['email'],
                    'message' => 'Cette adresse email est déjà utilisée !',
                ]),
            ],
            'data_class' => User::class,
        ]);
    }
}
