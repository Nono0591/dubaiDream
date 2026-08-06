<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(
        Request $request,
        MailerInterface $mailer
    ): Response {

        $form = $this->createForm(ContactType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();

            $email = (new Email())
                // ⚠️ Cette adresse doit être validée dans Mailjet
                ->from('johndoe.laboutiquefrancaise59@gmail.com')
                ->replyTo($data['email'])
                ->to('johndoe.laboutiquefrancaise59@gmail.com')
                ->subject($data['subject'])
                ->html('
                    <h2>Nouveau message depuis Dubai Dream</h2>
                    <p><strong>Nom :</strong> '.$data['lastname'].'</p>
                    <p><strong>Prénom :</strong> '.$data['firstname'].'</p>
                    <p><strong>Email :</strong> '.$data['email'].'</p>
                    <p><strong>Sujet :</strong> '.$data['subject'].'</p>
                    <hr>
                    <p>'.nl2br(htmlspecialchars($data['message'])).'</p>
                ');

            $mailer->send($email);
            $this->addFlash(
                'success',
                'Votre message a bien été envoyé.'
            );

            return $this->redirectToRoute('app_contact');
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}