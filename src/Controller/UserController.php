<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\User;
use App\Form\ProfilType;
use App\Form\UserCreationType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class UserController
 * @package App\Controller
 */
class UserController extends AbstractController
{
    /**
     * @var UserRepository
     */
    private $repository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * UserController constructor.
     *
     * @param UserRepository         $repository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(UserRepository $repository, EntityManagerInterface $entityManager)
    {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/membre/profil", name="membre.profil", methods={"GET","POST"})
     *
     * @param Request                      $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     *
     * @return Response
     */
    public function profil(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(ProfilType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $request->request->all();
            $data['profil']['avatar'] = $request->files->get('profil')['avatar'];
            $data = $data['profil'];
            $error = false;

            // Mise à jour de l'adresse email
            $email = $data['email'];
            if (!empty($email) && $email != $user->getEmail()) {
                $user->setEmail($email);
            }

            // Mise à jour de l'avatar
            $originalAvatar = $data['original_avatar'];
            $avatar = $data['avatar'];
            if (!is_null($avatar)) {
                // Chemin de l'avatar
                $path = 'uploads'.DIRECTORY_SEPARATOR.'users'.DIRECTORY_SEPARATOR.$user->getId().DIRECTORY_SEPARATOR;

                // Upload de l'avatar
                $avatarNewFilename = md5(uniqid()).'.'.$avatar->guessExtension();
                $avatarPath = $path.$avatarNewFilename;
                try {
                    // Déplacement de l'avatar sur le serveur
                    $avatar->move($path, $avatarNewFilename);
                } catch (FileException $e) {
                    $error = true;
                    $this->addFlash('danger', $e->getMessage());
                    return $this->redirectToRoute('membre.profil');
                }
                // Remplacement de l'avatar
                if (!empty($originalAvatar)) {
                    unlink($originalAvatar);
                }
                $user->setAvatar($avatarPath);
            } else {
                if (!empty($originalAvatar)) {
                    $originalAvatar = str_replace('/', '', $originalAvatar);
                    $user->setAvatar($originalAvatar);
                }
            }

            // Mise à jour du mot de passe
            $actualPassword = $data['actual_password'];
            $newPasswordFirst = $data['new_password']['first'];
            $newPasswordSecond = $data['new_password']['second'];
            if (!empty($actualPassword) && !empty($newPasswordFirst) && !empty($newPasswordSecond)) {
                if ($passwordEncoder->isPasswordValid($user, $actualPassword)) {
                    if ($newPasswordFirst === $newPasswordSecond) {
                        $user->setPassword($passwordEncoder->encodePassword($user, $newPasswordFirst));
                    } else {
                        $error = true;
                        $this->addFlash('danger', 'Le nouveau mot de passe n\'a pas été confirmé');
                        return $this->redirectToRoute('membre.profil');
                    }
                } else {
                    $error = true;
                    $this->addFlash('danger', 'Le mot de passe actuel est incorrect');
                    return $this->redirectToRoute('membre.profil');
                }
            }

            if (!$error) {
                $user->setUpdatedAt();
                // Mise à jour de l'utilisateur
                $this->entityManager->flush();
                $this->addFlash('success', 'Vos informations ont été mise à jour avec succès !');
                return $this->redirectToRoute('membre.profil');
            }
        }

        $categories_navbar = $this->entityManager->getRepository(Category::class)->findAll();

        return $this->render('user/profil.html.twig', ['current_menu'=>'profil', 'categories_navbar'=>$categories_navbar, 'user' => $user, 'form'=>$form->createView()]);
    }

    /**
     * @Route("/admin/utilisateurs", name="admin.users", methods={"GET"})
     *
     * @return Response
     */
    public function users(): Response
    {
        //$users = $this->repository->findBy([], ['username' => 'ASC']);
        $users = $this->repository->findAll();
        $user_connected = $this->getUser();
        $categories_navbar = $this->entityManager->getRepository(Category::class)->findAll();

        return $this->render('user/users.html.twig', ['current_menu'=>'users', 'categories_navbar'=>$categories_navbar, 'users' => $users, 'user_connected' => $user_connected]);
    }

    /**
     * @Route("/admin/utilisateur/creation", name="admin.user.create", methods={"GET","POST"})
     *
     * @param Request                      $request
     * @param ValidatorInterface           $validator
     * @param UserPasswordEncoderInterface $passwordEncoder
     *
     * @return Response
     */
    public function create(Request $request, ValidatorInterface $validator, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new User();
        $form = $this->createForm(UserCreationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user
                ->setUsername($form->get('username')->getData())
                ->setEmail($form->get('email')->getData())
                ->setPassword(
                    $passwordEncoder->encodePassword($user, $form->get('password')->getData())
                )
                ->setRoles([$form->get('roles')->getData()])
                ->setIsActive(true)
                ->setIsVerified(true)
                ->setIsVerifiedAt()
            ;

            $errors = $validator->validate($user);
            if (count($errors) > 0) {
                return new Response((string) $errors, 400);
            } else {
                $this->entityManager->persist($user);
                $this->entityManager->flush();
                $this->addFlash('success', 'L\'utilisateur '.$user->getUsername().' a été créée avec succès !');
                return $this->redirectToRoute('admin.users');
            }
        }

        $categories_navbar = $this->entityManager->getRepository(Category::class)->findAll();

        return $this->render('user/create.html.twig', ['categories_navbar'=>$categories_navbar, 'user'=>$user, 'form'=>$form->createView()]);
    }

    /**
     * @Route("/admin/utilisateur/modification/{id}", name="admin.user.edit", methods={"GET","POST"})
     *
     * @param Request                      $request
     * @param User                         $user
     * @param UserPasswordEncoderInterface $passwordEncoder
     *
     * @return Response
     */
    public function edit(Request $request, User $user, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $form = $this->createForm(UserCreationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user
                ->setUsername($form->get('username')->getData())
                ->setEmail($form->get('email')->getData())
                ->setRoles([$form->get('roles')->getData()])
            ;

            $password = $form->get('password')->getData();
            if ($password !== 'nopassword') {
                $user->setPassword(
                    $passwordEncoder->encodePassword($user, $password)
                );
            } else {
                $user->setPassword($request->request->get('user_password'));
            }

            $user->setUpdatedAt();
            // Mise à jour de la figure
            $this->entityManager->flush();
            $this->addFlash('success', 'L\'utilisateur '.$user->getUsername().' a été mis à jour avec succès !');
            return $this->redirectToRoute('admin.users');
        }

        $categories_navbar = $this->entityManager->getRepository(Category::class)->findAll();

        return $this->render('user/create.html.twig', ['categories_navbar'=>$categories_navbar, 'user'=>$user, 'form'=>$form->createView()]);
    }

    /**
     * @Route("/ajax/user/delete/{id}", name="user.ajax.delete", methods={"DELETE"})
     *
     * @param Request $request
     * @param User    $user
     *
     * @return bool|JsonResponse
     */
    public function delete(Request $request, User $user): JsonResponse
    {
        $isAjax = $request->isXmlHttpRequest();
        if ($isAjax) {
            $findUser = $this->repository->find($user->getId());
            if ($findUser) {
                $this->entityManager->remove($user);
                $this->entityManager->flush();

                return new JsonResponse(['success' => true]);
            }
        }
        return false;
    }

    /**
     * @Route("/ajax/user/active", name="user.ajax.active", methods={"POST"})
     *
     * @param Request $request
     *
     * @return bool|JsonResponse
     */
    public function ajaxActiveUser(Request $request): JsonResponse
    {
        $isAjax = $request->isXmlHttpRequest();
        if ($isAjax) {
            $data = $request->request->all();

            $user = $this->repository->find($data['user_id']);
            if ($user) {
                $active = $user->getIsActive() === true ? false : true;
                $user->setIsActive($active);
                $this->entityManager->flush();
                return new JsonResponse(['success' => true]);
            }
        }
        return false;
    }
}
