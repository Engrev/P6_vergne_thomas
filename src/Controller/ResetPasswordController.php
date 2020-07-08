<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\User;
use App\Form\ChangePasswordFormType;
use App\Form\ResetPasswordRequestFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use SymfonyCasts\Bundle\ResetPassword\Controller\ResetPasswordControllerTrait;
use SymfonyCasts\Bundle\ResetPassword\Exception\ResetPasswordExceptionInterface;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;

/**
 * Class ResetPasswordController
 * @package App\Controller
 */
class ResetPasswordController extends AbstractController
{
    use ResetPasswordControllerTrait;

    /**
     * @var ResetPasswordHelperInterface
     */
    private $resetPasswordHelper;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * ResetPasswordController constructor.
     *
     * @param ResetPasswordHelperInterface $resetPasswordHelper
     */
    public function __construct(ResetPasswordHelperInterface $resetPasswordHelper, EntityManagerInterface $entityManager)
    {
        $this->resetPasswordHelper = $resetPasswordHelper;
        $this->entityManager = $entityManager;
    }

    /**
     * Display & process form to request a password reset.
     *
     * @Route("/mot-de-passe-oublie", name="forgot_password_request")
     *
     * @param Request         $request
     * @param MailerInterface $mailer
     *
     * @return Response
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function request(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ResetPasswordRequestFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->entityManager->getRepository(User::class)->findOneBy(['username' => $form->get('username')->getData()]);
            $email = !is_null($user) ? $user->getEmail() : '';
            return $this->processSendingPasswordResetEmail($email, $mailer);
        }

        $categories_navbar = $this->entityManager->getRepository(Category::class)->findAll();

        return $this->render('reset_password/request.html.twig', ['categories_navbar' => $categories_navbar, 'form' => $form->createView()]);
    }

    /**
     * Confirmation page after a user has requested a password reset.
     *
     * @Route("/check-email", name="check_email")
     *
     * @return Response
     */
    public function checkEmail(): Response
    {
        // We prevent users from directly accessing this page
        if (!$this->canCheckEmail()) {
            return $this->redirectToRoute('forgot_password_request');
        }

        $categories_navbar = $this->entityManager->getRepository(Category::class)->findAll();

        return $this->render('reset_password/check_email.html.twig', [
            'categories_navbar' => $categories_navbar,
            'tokenLifetime' => $this->resetPasswordHelper->getTokenLifetime(),
        ]);
    }

    /**
     * Validates and process the reset URL that the user clicked in their email.
     *
     * @Route("/reinitialisation-mot-de-passe/{token}", name="reset_password")
     *
     * @param Request                      $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param string|null                  $token
     *
     * @return Response
     */
    public function reset(Request $request, UserPasswordEncoderInterface $passwordEncoder, string $token = null): Response
    {
        if ($token) {
            // We store the token in session and remove it from the URL, to avoid the URL being
            // loaded in a browser and potentially leaking the token to 3rd party JavaScript.
            $this->storeTokenInSession($token);

            return $this->redirectToRoute('reset_password');
        }

        $token = $this->getTokenFromSession();
        if (null === $token) {
            throw $this->createNotFoundException('No reset password token found in the URL or in the session.');
        }

        try {
            $user = $this->resetPasswordHelper->validateTokenAndFetchUser($token);
        } catch (ResetPasswordExceptionInterface $e) {
            $this->addFlash('reset_password_error', sprintf(
                'There was a problem validating your reset request - %s',
                $e->getReason()
            ));

            return $this->redirectToRoute('forgot_password_request');
        }

        // The token is valid; allow the user to change their password.
        $form = $this->createForm(ChangePasswordFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // A password reset token should be used only once, remove it.
            $this->resetPasswordHelper->removeResetRequest($token);

            // Encode the plain password, and set it.
            $encodedPassword = $passwordEncoder->encodePassword(
                $user,
                $form->get('password')->getData()
            );

            $user->setPassword($encodedPassword);
            $this->getDoctrine()->getManager()->flush();

            // The session is cleaned up after the password has been changed.
            $this->cleanSessionAfterReset();

            $this->addFlash('success', 'Votre mot de passe a été réinitialiser avec succès.');
            return $this->redirectToRoute('login');
        }

        $categories_navbar = $this->entityManager->getRepository(Category::class)->findAll();

        return $this->render('reset_password/reset.html.twig', [
            'categories_navbar' => $categories_navbar,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param string          $emailFormData
     * @param MailerInterface $mailer
     *
     * @return RedirectResponse
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    private function processSendingPasswordResetEmail(string $emailFormData, MailerInterface $mailer): RedirectResponse
    {
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy([
            'email' => $emailFormData,
        ]);

        // Marks that you are allowed to see the check_email page.
        $this->setCanCheckEmailInSession();

        // Do not reveal whether a user account was found or not.
        if (!$user) {
            $this->addFlash('reset_password_error', 'Aucun compte n\'a été trouvé.');
            return $this->redirectToRoute('forgot_password_request');
        }

        try {
            $resetToken = $this->resetPasswordHelper->generateResetToken($user);
        } catch (ResetPasswordExceptionInterface $e) {
            $this->addFlash('reset_password_error', sprintf(
                'There was a problem handling your password reset request - %s',
                $e->getReason()
            ));

            return $this->redirectToRoute('forgot_password_request');
        }

        $email = (new TemplatedEmail())
            ->from(new Address('no-reply@engrev.fr', 'Snowtricks'))
            ->to($user->getEmail())
            ->subject('Réinitialisation de votre mot de passe')
            ->htmlTemplate('reset_password/reset_email.html.twig')
            ->context([
                'resetToken' => $resetToken,
                'tokenLifetime' => $this->resetPasswordHelper->getTokenLifetime(),
                'username' => $user->getUsername()
            ])
        ;

        $mailer->send($email);

        return $this->redirectToRoute('check_email');
    }
}
