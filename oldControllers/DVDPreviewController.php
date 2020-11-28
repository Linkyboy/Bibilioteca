<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\DVDRepository;
use App\Repository\ArtistRepository;
use App\Entity\DVD;
class DVDPreviewController extends AbstractController
{
    /**
     * @Route("/dvd_preview/{id}", name="dvd_preview")
     */
    public function index(ArtistRepository $ar,DVDRepository $dr, int $id): Response
    {
        $artist = $ar->getBookAuthor($id);
        $dvd= $dr->findById($id);
        return $this->render('dvd_preview/index.html.twig', [
            'artist'=>$artist,
            'dvd'=>$dvd
        ]);
    }
}
