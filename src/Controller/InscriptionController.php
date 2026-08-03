<?php

namespace App\Controller;

use App\Classe\Mail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\InscriptionUserType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;



final class InscriptionController extends AbstractController
{
    #[Route('/inscription', name: 'app_inscription')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {

        $user = new User();

        $form = $this->createForm(InscriptionUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Votre addresse a bien été créé'
            );


            // Envoie d'un email de confirmation d'inscription
            $mail = new Mail();
            $vars = [
                'firstname' => $user->getFirstname()
            ];
            $mail->send($user->getEmail(), $user->getFirstname() . ' ' . $user->getLastname(), 'Bienvenue sur la boutique Française', 'welcome.html', $vars);

            return $this->redirectToRoute('app_login');
        }

        return $this->render('inscription/index.html.twig', [
            'inscriptionForm' => $form->createView(),
        ]);
    }
}
