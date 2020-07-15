<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Figure;
use App\Form\FigureCreationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\FigureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class FigureController
 * @package App\Controller
 */
class FigureController extends AbstractController
{
    /**
     * @var FigureRepository
     */
    private $repository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * FigureController constructor.
     *
     * @param FigureRepository       $repository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(FigureRepository $repository, EntityManagerInterface $entityManager)
    {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/", name="index", methods={"GET"})
     *
     * @return Response
     */
    public function index(): Response
    {
        $categories_navbar = $this->entityManager->getRepository(Category::class)->findAll();
        $figures = $this->repository->findAll();

        return $this->render('figures/index.html.twig', ['current_menu'=>'index', 'categories_navbar'=>$categories_navbar, 'figures'=>$figures]);
    }

    /**
     * @Route("/creation-figure", name="figure.create", methods={"GET","POST"})
     * @param Request            $request
     * @param ValidatorInterface $validator
     *
     * @return Response
     */
    public function create(Request $request, ValidatorInterface $validator): Response
    {
        $form = $this->createForm(FigureCreationFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $figure = (new Figure())
                ->setIdCategory($form->get('id_category')->getData()->getId())
                ->setName($form->get('name')->getData())
                ->setDescription($form->get('description')->getData())
                ->setAuthor($this->getUser()->getId());

            $errors = $validator->validate($figure);
            if (count($errors) > 0) {
                return new Response((string) $errors, 400);
            } else {
                $this->entityManager->persist($figure);
                $this->entityManager->flush();
                $this->addFlash('success', 'La figure a été créée avec succès !');
            }
        }

        $categories_navbar = $this->entityManager->getRepository(Category::class)->findAll();

        return $this->render('figures/create.html.twig', ['current_menu'=>'create_figure', 'categories_navbar'=>$categories_navbar, 'form'=>$form->createView()]);
    }
}