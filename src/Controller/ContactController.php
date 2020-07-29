<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Contact;
use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ContactController
 * @package App\Controller
 */
class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact", methods={"GET","POST"})
     *
     * @param Request         $request
     * @param MailerInterface $mailer
     *
     * @return Response
     * @throws TransportExceptionInterface
     */
    public function index(Request $request, MailerInterface $mailer): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email = (new Email())
                ->from(new Address($form->get('email')->getData(), $form->get('name')->getData()))
                ->to(new Address('local@engrev.fr'))
                ->replyTo(new Address($form->get('email')->getData()))
                ->subject($form->get('subject')->getData())
                ->text(nl2br($form->get('message')->getData()))
                ->html(nl2br($form->get('message')->getData()));
            $mailer->send($email);

            $this->addFlash('success', 'Votre email a été envoyé avec succès. Nous vous répondrons dans les plus brefs délais.');
            return $this->redirectToRoute('index');
        }

        $categories_navbar = $this->getDoctrine()->getRepository(Category::class)->findAll();

        return $this->render('contact/contact.html.twig', ['current_menu'=>'contact', 'categories_navbar'=>$categories_navbar, 'form'=>$form->createView()]);
    }
}