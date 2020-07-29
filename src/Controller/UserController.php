<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\ProfilType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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
    public function index(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
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
                    $this->redirectToRoute('membre.profil');
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
                        $this->redirectToRoute('membre.profil');
                    }
                } else {
                    $error = true;
                    $this->addFlash('danger', 'Le mot de passe actuel est incorrect');
                    $this->redirectToRoute('membre.profil');
                }
            }

            if (!$error) {
                $user->setUpdatedAt();
                // Mise à jour de l'utilisateur
                $this->entityManager->flush();
                $this->addFlash('success', 'Vos informations ont été mise à jour avec succès !');
                $this->redirectToRoute('membre.profil');
            }
        }

        $categories_navbar = $this->entityManager->getRepository(Category::class)->findAll();

        return $this->render('user/profil.html.twig', ['current_menu'=>'profil', 'categories_navbar'=>$categories_navbar, 'user' => $user, 'form'=>$form->createView()]);
    }
}
