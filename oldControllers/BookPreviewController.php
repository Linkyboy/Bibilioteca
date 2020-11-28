<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Artist;
use App\Entity\Book;
use App\Entity\Copy;
use App\Repository\BookRepository;
use App\Repository\ArtistRepository;
use App\Repository\CopyRepository;
class BookPreviewController extends AbstractController
{
    /**
     * @Route("/book/preview/{id}", name="book_preview")
     */
    public function index(int $id,ArtistRepository $ar,BookRepository $br): Response
    {
        $artist = $ar->getDocumentArtists(Book::class,$id);
        $book = $br->findById($id);
        return $this->render('book_preview/index.html.twig', [
            'artist'=>$artist[0],
            'book'=>$book[0]
        ]);
    }
    public function getBookCopyAvailable(int $bookID,CopyRepository $cr){
        $copies = $cr->getTotalAvailableCopies($bookID);
        return new Response($copies[0]["count"]);
    }
}
