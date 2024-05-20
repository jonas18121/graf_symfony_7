<?php

namespace App\Controller;

use App\DTO\ContactDTO;
use App\Form\ContactType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Mime\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function contact(
        Request $request,
        MailerInterface $mailer
    ): Response
    {
        $data = new ContactDTO();

        $form = $this->createForm(ContactType::class, $data);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            try {
                $email = (new TemplatedEmail())
                    ->from($data->email)
                    ->to($data->service)
                    //->cc('cc@example.com')
                    //->bcc('bcc@example.com')
                    //->replyTo('fabien@example.com')
                    //->priority(Email::PRIORITY_HIGH)
                    ->subject('Demande de contact')
                    //->text('Sending emails is fun again!')
                    //->html('<p>See Twig integration for better HTML integration!</p>')
                    ->htmlTemplate('emails/contact.html.twig')
                    ->context(['data' => $data]);

                $mailer->send($email);

                $this->addFlash('success', 'Le mail a bien été envoyé');
                return $this->redirectToRoute('app_contact');
            } 
            catch (\Exception $exception) {
                $this->addFlash('danger', 'Impossible d\'envoyé votre email');
            }            
        }

        return $this->render('contact/contact.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
