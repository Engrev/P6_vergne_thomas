<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

/**
 * Class RegistrationController
 * @package App\Controller
 */
class RegistrationController extends AbstractController
{
    /**
     * @var EmailVerifier
     */
    private $emailVerifier;

    /**
     * RegistrationController constructor.
     *
     * @param EmailVerifier $emailVerifier
     */
    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    /**
     * @Route("/inscription", name="register", methods={"GET","POST"})
     *
     * @param Request                      $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param EntityManagerInterface       $entityManager
     *
     * @return Response
     * @throws TransportExceptionInterface
     */
    public function index(Request $request, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user
                ->setUsername($form->get('username')->getData())
                ->setEmail($form->get('email')->getData())
                ->setPassword(
                    $passwordEncoder->encodePassword($user, $form->get('password')->getData())
                )
                ->setRoles()
            ;

            $entityManager->persist($user);
            $entityManager->flush();

            // generate a signed url and email it to the user
            $this->emailVerifier->sendEmailConfirmation('verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('no-reply@engrev.fr', 'Snowtricks'))
                    ->to($user->getEmail())
                    ->subject('Création de votre compte')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
                    ->context(['username' => $user->getUsername()])
            );

            $this->addFlash('success', 'Un email de confirmation vous a été envoyé.');

            return $this->redirectToRoute('index');
        }

        $categories_navbar = $entityManager->getRepository(Category::class)->findAll();

        return $this->render('registration/register.html.twig', ['current_menu' => 'register', 'categories_navbar' => $categories_navbar, 'form' => $form->createView()]);
    }

    /**
     * @Route("/verify/email", name="verify_email")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function verifyUserEmail(Request $request): Response
    {
        //$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['username' => $request->query->get('user')]);
        //$request->query->remove('user');
        $request->server->set('QUERY_STRING', str_replace('&user='.$user->getUsername(), '', $request->server->get('QUERY_STRING')));

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('danger', $exception->getReason());

            return $this->redirectToRoute('register');
        }

        $this->addFlash('success', 'Votre adresse email a bien été vérifiée.');

        return $this->redirectToRoute('index');
    }
}
