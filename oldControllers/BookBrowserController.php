<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Repository\ArtistRepository;
use App\Repository\BookRepository;
use App\Entity\Book;

/**
     * @Route("/book",name="book_browser")
     */
class BookBrowserController extends AbstractController
{
    /**
     * @Route("/all/{pagination}", name="book")
     */
    public function index(BookRepository $br,int $pagination=0): Response
    {
        $books= $br->getPaginatedBook($pagination,9);
        return $this->render('book_browser/index.html.twig', [
            'browseType'=>"document",
            "books"=>$books,
            "currentPage"=>$pagination
        ]);
    }
    /**
     * @Route("/artists", name="byArtists")
     */
    public function browseByArtist(ArtistRepository $ar): Response
    {
        $artists = $ar-> findAllEntityArtistAlphabetical(Book::class);
        return $this->render('book_browser/index.html.twig', [
            'artists'=>$artists,
            'browseType'=>"artist",
            "currentPage"=>0
        ]);
    }
    /**
     * @Route("/search/",name="search")
     *
     * @return void
     */
    public function searchResult(Request $request,BookRepository $br){

        $books = $br->getBookWithString($request->request->get('form')['query']);
        return $this->render('book_browser/index.html.twig', [
            'books'=>$books,
            'browseType'=>"document",
            "currentPage"=>0
        ]);
        return new Response();
    }
   
    /**
     * searchBar
     *
     * @return void
     */
    public function searchBar(){
        $form = $this->createFormBuilder(null)
                ->setAction($this->generateUrl('book_browsersearch'))
                ->setAttribute("class","form-inline")
                ->add('query', TextType::class,
                [ 'attr'=>[
                    "class"=>"form-control"
                ]])
                ->add("Q",SubmitType::class,[
                    'attr'=>[
                        'class'=>'btn btn-primary',
                    ]
                ])
                ->getForm(); 
       
        return $this->render('searchBar.html.twig',[
            'form'=>$form->createView()
        ]);
    }
    public function getArtistBook(int $id,BookRepository $br):Response{
        $books = $br->getArtistsBook($id);
        $response = "<ul>";
        foreach($books as $b){
            $response .= '<li> <a href="'.$this->generateUrl('book_preview', ['id' => $b->getId()]).'">'.$b->getTitle().'</a></li>';
        }
        $response.="</ul>";
        return new Response($response);
    }
    
}
