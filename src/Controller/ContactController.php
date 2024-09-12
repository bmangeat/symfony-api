<?php

namespace App\Controller;

use App\Dto\Request\ContactFormDto;
use App\Form\ContactType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact.index')]
    public function index(Request $request, ContactFormDto $contactFormDto, MailerInterface $mailer): Response
    {
        $data = new ContactFormDto();

        $form = $this->createForm(ContactType::class, $data);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
            $email = (new TemplatedEmail())
                    ->from('fds')
                    ->to('dfds')
                    ->subject('Demande de contact')
                    ->htmlTemplate('emails/contact.html.twig')
                    ->context(['data' => $data]);

                $mailer->send($email);
                $this->addFlash(
                    'success', 
                    'Votre mail a bien été envoyé.'
                );
                return $this->redirectToRoute('contact.index');
            } catch (\Exception $e) {
                $this->addFlash(
                   'danger',
                   "Impossible d'envoyer l'email"
                );
            }
        }
        return $this->render('contact/index.html.twig', [
            'form' => $form,
        ]);
    }
}
