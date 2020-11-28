<?php

namespace App\Controller;

use App\Repository\CDRepository;
use App\Repository\DVDRepository;
use App\Repository\BookRepository;
use App\Repository\CopyRepository;
use App\Repository\ArtistRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DocumentPreviewController extends AbstractController
{
    /**
     * @Route("/preview/{docType}/{id}", name="document_preview")
     */
    public function index(string $docType,int $id, CDRepository $cr, DVDRepository $dr,BookRepository $br, ArtistRepository $ar): Response
    {
        $document = null;
        switch($docType){
            case "books":
                $document = $br->findOneBy(["id"=>$id]);
            break;
            case "cd":
                $document = $cr->findOneBy(["id"=>$id]);
            break;
            case "dvd":
                $document = $dr->findOneBy(["id"=>$id]);
            break;
        }
        $artist = $ar->getDocumentArtists($id);
        return $this->render('document_preview/index.html.twig', [
            "document"=>$document,
            "artists"=>$artist
        ]);
    }
    public function getDocumentCopyAvailable(int $docID,CopyRepository $cr){
        $copies = $cr->getTotalAvailableCopies($docID);
        return new Response($copies[0]["count"]);
    }
}
