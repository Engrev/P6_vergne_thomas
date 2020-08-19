<?php

namespace App\Service;

use App\Entity\Figure;
use App\Entity\File;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\ControllerTrait;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class FigureManager
 * @package App\Service
 */
class FigureManager extends AbstractController
{
    //use ControllerTrait;

    /**
     * @param Figure  $figure
     * @param         $form
     * @param Request $request
     * @param string  $action
     */
    public function set(Figure $figure, $form, Request $request, string $action)
    {
        // Chemin de l'upload
        $path = 'uploads'.DIRECTORY_SEPARATOR.'figures'.DIRECTORY_SEPARATOR.$figure->getId().DIRECTORY_SEPARATOR;

        // Upload de l'image principale
        switch ($action) {
            case 'create':
                $cover = $form->get('picture')->getData();
                $coverNewFilename = md5(uniqid()).'.'.$cover->guessExtension();
                $coverPath = $path.$coverNewFilename;
                try {
                    // Déplacement de l'image principale sur le serveur
                    $cover->move($path, $coverNewFilename);
                } catch (FileException $e) {
                    $this->addFlash('danger', $e->getMessage());
                    return $this->redirectToRoute('figure.create');
                }
                // Préparation pour l'enregistrement en bdd
                $figure->setPicture($coverPath);
                break;
            case 'edit':
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
                        return $this->redirectToRoute('figure.edit', ['id' => $figure->getId()]);
                    }
                    // Remplacement de l'image principale
                    if (!empty($originalPicture)) {
                        unlink($originalPicture);
                    }
                    $figure->setPicture($coverPath);
                } else {
                    $figure->setPicture($originalPicture);
                }
                break;
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
                    return $this->redirectToRoute('figure.create');
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
                    if (preg_match('#&t=[0-9]+s$#', $lien, $matches) || preg_match('#&feature=youtu\.be$#', $lien, $matches)) {
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
                        ->setPath('https://www.youtube.com/embed/'.$newVideoname)
                        ->setName($newVideoname)
                        ->setUploadedName($newVideoname);
                    $figure->addFile($file);
                }
            }
        }

        if ($action === 'edit') {
            $figure->setUpdatedAt();
        }
    }
}