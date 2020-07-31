<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Figure;
use App\Entity\File;
use App\Entity\Message;
use App\Entity\User;
use App\Form\FigureCreationType;
use App\Helper\TimeSinceCreationTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
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
    use TimeSinceCreationTrait;

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
     *
     * @param Request            $request
     * @param ValidatorInterface $validator
     *
     * @return Response
     */
    public function create(Request $request, ValidatorInterface $validator): Response
    {
        $figure = new Figure();
        $form = $this->createForm(FigureCreationType::class, $figure);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category = $this->entityManager->getRepository(Category::class)->findOneBy(['id' => $form->get('category')->getData()->getId()]);
            $user = $this->entityManager->getRepository(User::class)->findOneBy(['id' => $this->getUser()->getId()]);
            // Création d'une figure
            $figure
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

                // Chemin de l'upload
                $path = 'uploads'.DIRECTORY_SEPARATOR.'figures'.DIRECTORY_SEPARATOR.$figure->getId().DIRECTORY_SEPARATOR;

                // Upload de l'image principale
                $cover = $form->get('picture')->getData();
                $coverNewFilename = md5(uniqid()).'.'.$cover->guessExtension();
                $coverPath = $path.$coverNewFilename;
                try {
                    // Déplacement de l'image principale sur le serveur
                    $cover->move($path, $coverNewFilename);
                } catch (FileException $e) {
                    $this->addFlash('danger', $e->getMessage());
                    $this->redirectToRoute('figure.create');
                }
                // Préparation pour l'enregistrement en bdd
                $figure->setPicture($coverPath);

                // Upload des photos
                $pictures = $form->get('files')->getData();
                if ($pictures) {
                    foreach ($pictures as $picture) {
                        $originalFilename = pathinfo($picture->getClientOriginalName(), PATHINFO_FILENAME);
                        $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                        $safeFilename = $safeFilename.'.'.$picture->guessExtension();
                        $newFilename = md5(uniqid()).'.'.$picture->guessExtension();
                        $picturePath = $path.$newFilename;
                        try {
                            // Déplacement de la photo sur le serveur
                            $picture->move($path, $newFilename);
                        } catch (FileException $e) {
                            $this->addFlash('danger', $e->getMessage());
                            $this->redirectToRoute('figure.create');
                        }
                        // Création d'un fichier
                        $file = (new File())
                            ->setFigure($figure)
                            ->setPath($picturePath)
                            ->setName($newFilename)
                            ->setUploadedName($safeFilename);
                        $figure->addFile($file);
                    }
                    $this->addFlash('success', 'La/les photo(s) a/ont été envoyée(s) avec succès !');
                }

                //Upload de(s) vidéo(s)
                $videosLink = $request->request->get('figure_creation_videolink');
                $videosCode = $request->request->get('figure_creation_videocode');
                $videos = array_merge($videosLink, $videosCode);
                if (!empty($videos)) {
                    foreach ($videos as $lien) {
                        if (!empty($lien)) {
                            if (preg_match('#&t=[0-9]+s$#', $lien, $matches)) {
                                $lien = str_replace($matches[0], '', $lien);
                            }
                            if (preg_match('#^https://www\.youtube\.com/watch\?v=[a-zA-Z0-9_]+$#', $lien)) {
                                $videoName = explode('?v=', $lien);
                            } elseif (preg_match('#^https://youtu\.be/[a-zA-Z0-9_]+$#', $lien)) {
                                $videoName = explode('be/', $lien);
                            } elseif (preg_match('#^<iframe#', $lien)) {
                                $parts = explode(' ', $lien);
                                foreach ($parts as $part) {
                                    if (preg_match('#^src=#', $part)) {
                                        $partName = str_replace('src=', '', $part);
                                        $partName = str_replace('"', '', $partName);
                                        $videoName = explode('embed/', $partName);
                                    }
                                }
                            } else {
                                $videoName = '';
                            }
                            $newVideoname = is_array($videoName) ? $videoName[1] : $videoName;
                            // Création d'un fichier
                            $file = (new File())
                                ->setFigure($figure)
                                ->setPath($lien)
                                ->setName($newVideoname)
                                ->setUploadedName($newVideoname);
                            $figure->addFile($file);
                        }
                    }
                }

                // Enregistrement des photos en bdd par la figure
                $this->entityManager->persist($figure);
                $this->entityManager->flush();
                $this->redirectToRoute('index');
            }
        }

        $categories_navbar = $this->entityManager->getRepository(Category::class)->findAll();

        return $this->render('figure/create.html.twig', ['current_menu'=>'figure_create', 'categories_navbar'=>$categories_navbar, 'figure'=>$figure, 'form'=>$form->createView()]);
    }

    /**
     * @Route("/figure/modification/{id}", name="figure.edit", methods={"GET","POST"})
     *
     * @param Request $request
     * @param Figure  $figure
     *
     * @return Response
     */
    public function edit(Request $request, Figure $figure): Response
    {
        $form = $this->createForm(FigureCreationType::class, $figure);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Chemin de l'upload
            $path = 'uploads'.DIRECTORY_SEPARATOR.'figures'.DIRECTORY_SEPARATOR.$figure->getId().DIRECTORY_SEPARATOR;

            // Upload de l'image principale
            $originalPicture = $form->get('original_picture')->getData();
            if (!empty($originalPicture)) {
                $originalPicture = str_replace('/', '', $originalPicture);
            }
            $cover = $form->get('picture')->getData();
            if (!is_null($cover)) {
                $coverNewFilename = md5(uniqid()).'.'.$cover->guessExtension();
                $coverPath = $path.$coverNewFilename;
                try {
                    // Déplacement de l'image principale sur le serveur
                    $cover->move($path, $coverNewFilename);
                } catch (FileException $e) {
                    $this->addFlash('danger', $e->getMessage());
                    $this->redirectToRoute('figure.edit', ['id' => $figure->getId()]);
                }
                // Remplacement de l'image principale
                if (!empty($originalPicture)) {
                    unlink($originalPicture);
                }
                $figure->setPicture($coverPath);
            } else {
                $figure->setPicture($originalPicture);
            }

            // Upload des photos
            $pictures = $form->get('files')->getData();
            if (!empty($pictures)) {
                foreach ($pictures as $picture) {
                    $originalFilename = pathinfo($picture->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                    $safeFilename = $safeFilename.'.'.$picture->guessExtension();
                    $newFilename = md5(uniqid()).'.'.$picture->guessExtension();
                    $picturePath = $path.$newFilename;
                    try {
                        // Déplacement de la photo sur le serveur
                        $picture->move($path, $newFilename);
                    } catch (FileException $e) {
                        $this->addFlash('danger', $e->getMessage());
                        $this->redirectToRoute('figure.edit', ['id' => $figure->getId()]);
                    }
                    // Création d'un fichier
                    $file = (new File())
                        ->setFigure($figure)
                        ->setPath($picturePath)
                        ->setName($newFilename)
                        ->setUploadedName($safeFilename);
                    $figure->addFile($file);
                }
            }

            //Upload de(s) vidéo(s)
            $videosLink = $request->request->get('figure_creation_videolink');
            $videosCode = $request->request->get('figure_creation_videocode');
            $videos = array_merge($videosLink, $videosCode);
            if (!empty($videos)) {
                foreach ($videos as $lien) {
                    if (!empty($lien)) {
                        if (preg_match('#&t=[0-9]+s$#', $lien, $matches)) {
                            $lien = str_replace($matches[0], '', $lien);
                        }
                        if (preg_match('#^https://www\.youtube\.com/watch\?v=[a-zA-Z0-9_]+$#', $lien)) {
                            $videoName = explode('?v=', $lien);
                        } elseif (preg_match('#^https://youtu\.be/[a-zA-Z0-9_]+$#', $lien)) {
                            $videoName = explode('be/', $lien);
                        } elseif (preg_match('#^<iframe#', $lien)) {
                            $parts = explode(' ', $lien);
                            foreach ($parts as $part) {
                                if (preg_match('#^src=#', $part)) {
                                    $partName = str_replace('src=', '', $part);
                                    $partName = str_replace('"', '', $partName);
                                    $videoName = explode('embed/', $partName);
                                }
                            }
                        } else {
                            $videoName = '';
                        }
                        $newVideoname = is_array($videoName) ? $videoName[1] : $videoName;
                        // Création d'un fichier
                        $file = (new File())
                            ->setFigure($figure)
                            ->setPath($lien)
                            ->setName($newVideoname)
                            ->setUploadedName($newVideoname);
                        $figure->addFile($file);
                    }
                }
            }

            $figure->setUpdatedAt();
            // Mise à jour de la figure
            $this->entityManager->flush();
            $this->addFlash('success', 'La figure a été mise à jour avec succès !');
            $this->redirectToRoute('index');
        }

        $categories_navbar = $this->entityManager->getRepository(Category::class)->findAll();

        return $this->render('figure/create.html.twig', ['categories_navbar'=>$categories_navbar, 'figure'=>$figure, 'form'=>$form->createView()]);
    }

    /**
     * @Route("/ajax/figure/show", name="figure.ajax.show", methods={"POST"})
     *
     * @param Request           $request
     *
     * @return bool|JsonResponse
     */
    public function ajaxShow(Request $request): JsonResponse
    {
        $isAjax = $request->isXmlHttpRequest();
        if ($isAjax) {
            $data = $request->request->all();

            $figure = $this->repository->findOneBy(['id' => $data['figure_id']]);
            $files = $this->entityManager->getRepository(File::class)->findBy(['figure' => $data['figure_id']]);
            $conversation = $this->entityManager->getRepository(Message::class)->findBy(['figure' => $data['figure_id']], ['created_at' => 'DESC'], $data['limit']);

            if (!empty($files)) {
                foreach ($files as $key => $file) {
                    $pictures[$key]['id'] = $file->getId();
                    $pictures[$key]['path'] = $file->getPath();
                    $pictures[$key]['uploaded_name'] = $file->getUploadedName();
                }
            } else {
                $pictures = '';
            }
            if (!empty($conversation)) {
                foreach ($conversation as $key => $message) {
                    $messages[$key]['id'] = $message->getId();
                    $messages[$key]['content'] = $message->getContent();
                    $messages[$key]['date'] = $this->dateSinceCreation($message->getCreatedAt()->format('Y-m-d H:i:s'));
                    $messages[$key]['user']['username'] = $message->getUser()->getUsername();
                    $messages[$key]['user']['avatar'] = $message->getUser()->getAvatar();
                }
            } else {
                $messages = '';
            }

            if (!is_null($figure)) {
                return new JsonResponse([
                    'figure' => [
                        'id' => $figure->getId(),
                        'name' => $figure->getName(),
                        'description' => $figure->getDescription(),
                        'cover' => $figure->getPicture(),
                        'created_at' => $figure->getCreatedAt()->format('d/m/Y à H:i'),
                        'updated_at' => $figure->getUpdatedAt()->format('d/m/Y à H:i'),
                        'user' => $figure->getUser()->getId(),
                        'categorie' => $figure->getCategory()->getName()
                    ],
                    'pictures' => $pictures,
                    'messages' => $messages
                ]);
            }
        }
        return false;
    }

    /**
     * @Route("/ajax/figure/delete/{id}", name="figure.ajax.delete", methods={"DELETE"})
     *
     * @param Request $request
     * @param Figure  $figure
     *
     * @return bool|JsonResponse
     */
    public function ajaxDelete(Request $request, Figure $figure): JsonResponse
    {
        $isAjax = $request->isXmlHttpRequest();
        if ($isAjax) {
            $findFigure = $this->repository->find($figure->getId());
            if ($findFigure) {
                $path = 'uploads'.DIRECTORY_SEPARATOR.'figures'.DIRECTORY_SEPARATOR;
                $directory = $path.$figure->getId();
                $this->deletePicturesDirectory($directory);
                if (file_exists($directory)) {
                    unlink($figure->getPicture());
                }

                $this->entityManager->remove($figure);
                $this->entityManager->flush();

                return new JsonResponse(['success' => true]);
            }
        }
        return false;
    }

    /**
     * @Route("/ajax/picture/delete/{id}", name="figure.ajax.delete.picture", methods={"DELETE"})
     *
     * @param Request $request
     * @param File    $file
     *
     * @return bool|JsonResponse
     */
    public function ajaxPictureDelete(Request $request, File $file): JsonResponse
    {
        $isAjax = $request->isXmlHttpRequest();
        if ($isAjax) {
            if (file_exists($file->getPath())) {
                unlink($file->getPath());
            }
            $file->getFigure()->setUpdatedAt();

            $this->entityManager->remove($file);
            $this->entityManager->flush();

            return new JsonResponse(['success' => true]);
        }
        return false;
    }

    /**
     * @param string $directory
     */
    private function deletePicturesDirectory(string $directory)
    {
        if (is_dir($directory)) {
            if ($dir = opendir($directory)) {
                // Vide tous les dossiers et sous dossiers
                while (($element = readdir($dir)) !== false) {
                    if (is_dir($directory.DIRECTORY_SEPARATOR.$element) && substr($element, -1, 1) !== '.' && substr($element, -2, 2) !== '..') {
                        $this->deletePicturesDirectory($directory.DIRECTORY_SEPARATOR.$element);
                    } else {
                        if (substr($element, -1, 1) !== '.' && substr($element, -2, 2) !== '..') {
                            unlink($directory.DIRECTORY_SEPARATOR.$element);
                        }
                    }
                }

                // Efface tous les dossiers
                while (($element = readdir($dir)) !== false) {
                    if (is_dir($directory.DIRECTORY_SEPARATOR.$element) && substr($element, -1, 1) !== '.' && substr($element, -2, 2) !== '..') {
                        $this->deletePicturesDirectory($directory.DIRECTORY_SEPARATOR.$element);
                        rmdir($directory.DIRECTORY_SEPARATOR.$element);
                    }
                }
                rmdir($directory);
            }
        }
    }
}