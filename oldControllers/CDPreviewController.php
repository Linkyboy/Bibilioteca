<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CDRepository;
use App\Repository\ArtistRepository;
use App\Entity\CD;
class CDPreviewController extends AbstractController
{
    /**
     * @Route("/cd_preview/{id}", name="cd_preview")
     */
    public function index(ArtistRepository $ar,CDRepository $cr,int $id): Response
    {
        
        $artist = $ar->getDocumentArtists(CD::class,$id);
        $cd= $cr->findById($id);
        dump($cd);
        return $this->render('cd_preview/index.html.twig', [
            'controller_name' => 'CDPreviewController',
            'artist'=>$artist[0],
            'cd'=>$cd[0]
        ]);
    }
}
