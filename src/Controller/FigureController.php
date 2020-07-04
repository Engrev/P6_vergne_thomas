<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     * @var CategoryRepository
     */
    private $categoryRepository;

    /**
     * @var FigureRepository
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
     * FigureController constructor.
     *
     * @param CategoryRepository     $categoryRepository
     * @param FigureRepository       $repository
     * @param EntityManagerInterface $entityManager
     * @param ValidatorInterface     $validator
     */
    public function __construct(CategoryRepository $categoryRepository, FigureRepository $repository, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $this->categoryRepository = $categoryRepository;
        $this->repository = $repository;
        $this->entityManager = $entityManager;
        $this->validator = $validator;
    }

    /**
     * @Route("/", name="index", methods={"GET"})
     * @return Response
     */
    public function index()
    {
        $categories_navbar = $this->categoryRepository->findAll();
        $figures = $this->repository->findAll();
        return $this->render('front/index.html.twig', ['current_menu'=>'homepage', 'categories_navbar'=>$categories_navbar, 'figures'=>$figures]);
    }
}
