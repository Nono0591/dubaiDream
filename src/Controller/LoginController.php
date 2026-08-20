<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


final class LoginController extends AbstractController
{
    #[Route('/connexion', name: 'app_login')]
    public function index(AuthenticationUtils $authenticationUtils, RequestStack $requestStack): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUsername();

        // Si l'utilisateur a été redirigé ici depuis le tunnel de commande
        // (accès refusé par le firewall car non authentifié), on l'informe
        // que son panier est conservé et qu'il reprendra sa commande après connexion.
        $targetPath = $requestStack->getSession()->get('_security.main.target_path');

        if ($targetPath && str_contains($targetPath, '/commande')) {
            $this->addFlash(
                'info',
                'Connectez-vous ou créez un compte pour finaliser votre commande. Votre panier est conservé.'
            );
        }

        return $this->render('login/index.html.twig', [
            'controller_name' => 'LoginController',
            'error' => $error,
            'lastUsername' => $lastUsername
        ]);
    }

    #[Route('/deconnexion', name: 'app_logout', methods: ['GET'])]
    public function logout(): void
    {
        // controller can be blank: it will never be called!
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}