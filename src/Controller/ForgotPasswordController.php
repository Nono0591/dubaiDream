<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Entity\User;
use App\Form\ForgotPasswordFormType;
use App\Form\ResetPasswordFormType;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class ForgotPasswordController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/mot-de-passe-oublie', name: 'app_password')]
    public function index(Request $request, UserRepository $userRepository): Response
    {
        // 1. Formulaire
        $form = $this->createForm(ForgotPasswordFormType::class);

        $form->handleRequest($request);
        //2. Traitementdu formulaire 
        if ($form->isSubmitted() && $form->isValid()) {

            //3. si email renseigné par utilisateur est en base de donnée
            $email = $form->get('email')->getData();
            $user = $userRepository->findOneByEmail($email);

            //4 si c'est le cas, on reset le mot de passe et on envoie un email à l'utilisateur avec le nouveau mot de passe

            $this->addFlash('success', 'Un email de réinitialisation de mot de passe a été envoyé à votre adresse email.');

            //5 si user existe , on reset le mot de passe et on envoie un email à l'utilisateur avec le nouveau mot de passe
            if ($user) {

            //5.1 Générer un token de réinitialisation de mot de passe qu'on stocke en base de données
                $token= bin2hex(random_bytes(15));
                $user->setToken($token);

                $date = new DateTime();

                $date->modify('+10 minutes');

                $this->entityManager->flush();
                
                $user->setTokenExpireAt($date);
                $this->entityManager->flush();

                $mail = new Mail();
                $vars = [
                    'link' => $this->generateUrl('app_password_reset', ['token' => $token],UrlGeneratorInterface::ABSOLUTE_URL),
                ];
                $mail->send($user->getEmail(), $user->getFirstname() . ' ' . $user->getLastname(), 'Réinitialisation de votre mot de passe', 'forgotpassword.html', $vars);
            }
        }
        return $this->render('password/index.html.twig', [
            'forgotPasswordForm' => $form->createView(),
        ]);
    }

    #[Route('/mot-de-passe/reset/{token}', name: 'app_password_reset')]
    public function resetPassword(Request $request, UserRepository $userRepository, string $token, User $user): Response
    {
        if (!$token) {
            return $this->redirectToRoute('app_password');
        }
        
        $now = new DateTime();

        if (!$user || $now > $user->getTokenExpireAt()){
            return $this->redirectToRoute('app_password');
        }
    
        $user = $userRepository->findOneBytoken($token);

        $form = $this->createForm(ResetPasswordFormType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setToken(null);
            $user->setTokenExpireAt(null);
            
            $this->entityManager->flush();
            $this->addFlash('success', 'Votre mot de passe a été modifié avec succès.'
            );
       

        return $this->redirectToRoute('app_login');
    
        }
        return $this->render('password/resetpassword.html.twig', [
            'form' => $form->createView(),
        ]);
        
    }
}