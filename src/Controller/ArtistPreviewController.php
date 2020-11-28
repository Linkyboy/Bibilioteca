<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArtistPreviewController extends AbstractController
{
    /**
     * @Route("/artist/preview/{id}", name="artist_preview")
     */
    public function index(): Response
    {
        return $this->render('artist_preview/index.html.twig', [
            'controller_name' => 'ArtistPreviewController',
        ]);
    }

    
}
