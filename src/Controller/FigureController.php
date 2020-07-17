<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Figure;
use App\Entity\File;
use App\Entity\User;
use App\Form\FigureCreationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\FigureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

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

        return $this->render('figure/index.html.twig', ['current_menu'=>'index', 'categories_navbar'=>$categories_navbar, 'figures'=>$figures]);
    }

    /**
     * @Route("/figure/creation", name="figure.create", methods={"GET","POST"})
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
            $category = $this->entityManager->getRepository(Category::class)->findOneBy(['id' => $form->get('id_category')->getData()->getId()]);
            $user = $this->entityManager->getRepository(User::class)->findOneBy(['id' => $this->getUser()->getId()]);
            // Création d'une figure
            $figure = (new Figure())
                ->setCategory($category)
                ->setName($form->get('name')->getData())
                ->setDescription($form->get('description')->getData())
                ->setUser($user);

            $errors = $validator->validate($figure);
            if (count($errors) > 0) {
                return new Response((string) $errors, 400);
            } else {
                // Enregistrement de la figure en bdd
                $this->entityManager->persist($figure);
                $this->entityManager->flush();
                $this->addFlash('success', 'La figure a été créée avec succès !');

                $pictures = $form->get('pictures')->getData();
                $path = 'uploads'.DIRECTORY_SEPARATOR.'figures'.DIRECTORY_SEPARATOR.$figure->getId();
                if ($pictures) {
                    foreach ($pictures as $picture) {
                        $originalFilename = pathinfo($picture->getClientOriginalName(), PATHINFO_FILENAME);
                        $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                        $safeFilename = $safeFilename.'.'.$picture->guessExtension();
                        $newFilename = md5(uniqid()).'.'.$picture->guessExtension();
                        $path .= DIRECTORY_SEPARATOR.$newFilename;
                        try {
                            // Déplacement du fichier sur le serveur
                            $picture->move($path, $newFilename);
                        } catch (FileException $e) {
                            $this->addFlash('danger', $e->getMessage());
                            $this->redirectToRoute('figure.create');
                        }
                        // Création d'un fichier
                        $file = (new File())
                            ->setFigure($figure)
                            ->setPath($path)
                            ->setName($newFilename)
                            ->setUploadedName($safeFilename);
                        $figure->addFile($file);
                    }
                    // Enregistrement des photos en bdd par la figure
                    $this->entityManager->persist($figure);
                    $this->entityManager->flush();
                    $this->addFlash('success', 'La/les photo(s) a/ont été envoyée(s) avec succès !');
                }
            }
        }

        $categories_navbar = $this->entityManager->getRepository(Category::class)->findAll();

        return $this->render('figure/create.html.twig', ['current_menu'=>'create_figure', 'categories_navbar'=>$categories_navbar, 'form'=>$form->createView()]);
    }

    /**
     * @Route("/ajax/figure/load", name="figure.ajax.load", methods={"POST"})
     * @param Request $request
     *
     * @return bool|JsonResponse
     */
    public function ajaxLoad(Request $request): JsonResponse
    {
        $isAjax = $request->isXmlHttpRequest();
        if ($isAjax) {
            $encoders = [new JsonEncoder()];
            $normalizers = [new ObjectNormalizer()];
            $serializer = new Serializer($normalizers, $encoders);

            $figure = $this->repository->findOneBy(['id' => $request->request->get('id_figure')]);
            if (!is_null($figure)) {
                $jsonFigure = $serializer->serialize($figure, 'json');
                return new JsonResponse($jsonFigure);
            }
        }
        return false;
    }
}