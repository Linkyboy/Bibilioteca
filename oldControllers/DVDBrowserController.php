<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Repository\ArtistRepository;
use App\Repository\DVDRepository; 
use App\Entity\DVD;

/**
     * @Route("/dvdbrowser",name="dvd_browser")
     */
class DVDBrowserController extends AbstractController
{
    /**
     * @Route("/all/{pagination}", name="dvd")
     */
    public function index(DVDRepository $br,int $pagination=0): Response
    {
        $dvds= $br->getPaginatedDVD($pagination,9);
        return $this->render('dvd_browser/index.html.twig', [
            'browseType'=>"document",
            "dvds"=>$dvds,
            "currentPage"=>$pagination
        ]);
    }
    /**
     * @Route("/artists", name="byArtists")
     */
    public function browseByArtist(ArtistRepository $ar): Response
    {
        $artists = $ar-> findAllEntityArtistAlphabetical(DVD::class);
        return $this->render('dvd_browser/index.html.twig', [
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
    public function searchResult(Request $request,DVDRepository $br){

        $dvds = $br->getDVDWithString($request->request->get('form')['query']);
        return $this->render('dvd_browser/index.html.twig', [
            'dvds'=>$dvds,
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
    public function getArtistDVD(int $id,DVDRepository $br):Response{
        $dvds = $br->getArtistsDVD($id);
        $response = "<ul>";
        foreach($dvds as $b){
            $response .= '<li> <a href="'.$this->generateUrl('dvd_preview', ['id' => $b->getId()]).'">'.$b->getTitle().'</a></li>';
        }
        $response.="</ul>";
        return new Response($response);
    }
    
}
