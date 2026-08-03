<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class NewsletterController extends AbstractController
{
    #[Route('/newsletter/subscribe', name: 'app_newsletter_subscribe', methods: ['POST'])]
    public function subscribe(Request $request): Response
    {
        $email = $request->request->get('email');

        // TODO : Traitement de l'email (enregistrement en BDD ou envoi d'email)

        $this->addFlash('success', 'Merci pour votre inscription à la newsletter !');

        return $this->redirectToRoute('app_home');
    }
}