<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CategoryRepository;
use App\Repository\ArtistRepository;
use App\Entity\Book;
class CategoryPreviewController extends AbstractController
{
    /**
     * @Route("/category/preview/{id}", name="category_preview")
     */
    public function index(int $id,CategoryRepository $cr,ArtistRepository $ar): Response
    {
        $category = $cr->findOneBy(["id"=>$id]);
        $artists =$ar->findAllEntityArtistInCategory($id,Book::class);
        return $this->render('category_preview/index.html.twig', [
            'controller_name' => 'CategoryPreviewController',
            'category'=>$category,
            'artists'=>$artists
        ]);
    }
}
