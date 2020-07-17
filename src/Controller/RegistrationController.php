<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\RegistrationForm;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\EmailVerifier;
use App\Security\LoginFormAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
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
     */
    public function index(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, LoginFormAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        $registrationForm = new RegistrationForm();
        $form = $this->createForm(RegistrationFormType::class, $registrationForm);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = new User();
            $user
                ->setUsername($form->get('username')->getData())
                ->setEmail($form->get('email')->getData())
                ->setPassword(
                    $passwordEncoder->encodePassword($user, $form->get('password')->getData())
                )
                ->setRoles()
                ->setLastConnectionAt()
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

            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );
        }

        $categories_navbar = $entityManager->getRepository(Category::class)->findAll();

        return $this->render('registration/register.html.twig', ['current_menu' => 'register', 'categories_navbar' => $categories_navbar, 'form' => $form->createView()]);
    }

    /**
     * @Route("/verify/email", name="verify_email")
     */
    public function verifyUserEmail(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $exception->getReason());

            return $this->redirectToRoute('register');
        }

        $this->addFlash('success', 'Votre adresse email a bien été vérifiée.');

        return $this->redirectToRoute('index');
    }
}
