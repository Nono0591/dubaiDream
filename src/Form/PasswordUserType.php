<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Validator\Constraints as assert;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\Form\FormError;


class PasswordUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder  
            ->add('actualPassword',PasswordType::class, [
                'label' => 'Mot de passe actuel',
                'attr' => [
                    'placeholder' => 'Veuillez saisir votre mot de passe actuel'
                    ],
                    'mapped' => false,
            ])

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

            ->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
                $form = $event->getForm();
                $user = $form->getConfig()->getOptions()['data'];
                $passwordHasher = $form->getConfig()->getOptions()['passwordHasher'];


                //1. Récupérer le mot de passe saisi par l'utilisateur et le comparer avec celui en base de données
                
                $isValid = $passwordHasher->isPasswordValid(
                    $user,
                    $form->get('actualPassword')->getData()
                );

    
                //3 si c'est !=, envoyer une erreur
                if (!$isValid) {
                    $form->get('actualPassword')->addError(new \Symfony\Component\Form\FormError("Le mot de passe actuel est incorrect."));
                    return;
                }
               
                
            })
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'passwordHasher' => null,
        ]);
    }
}
