<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * UserController constructor.
     *
     * @param UserRepository         $repository
     * @param EntityManagerInterface $entityManager
     * @param ValidatorInterface     $validator
     */
    public function __construct(UserRepository $repository, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
        $this->validator = $validator;
    }

    /**
     * @Route("/admin/creation-utilisateur", name="admin.create.user", methods={"GET"})
     * @param EntityManagerInterface $entityManager
     * @param ValidatorInterface     $validator
     *
     * @return Response
     */
    public function createUser()
    {
        $user = new User();

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $this->entityManager->persist($user);
        // actually executes the queries (i.e. the INSERT query)
        $this->entityManager->flush();

        $errors = $this->validator->validate($user);
        if (count($errors) > 0) {
            return new Response((string) $errors, 400);
        }

        $categories_navbar = $this->entityManager->getRepository(Category::class)->findAll();

        $this->addFlash('success', 'L\'utilisateur a été créé avec succès !');
        return $this->render('pages/index.html.twig', ['current_menu'=>'dashboard', 'categories_navbar'=>$categories_navbar]);
    }
}
