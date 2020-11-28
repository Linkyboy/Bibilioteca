<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Repository\ArtistRepository;
use App\Repository\CDRepository; 
use App\Entity\CD;

/**
     * @Route("/cdbrowser",name="cd_browser")
     */
class CDBrowserController extends AbstractController
{
    /**
     * @Route("/all/{pagination}", name="cd")
     */
    public function index(CDRepository $br,int $pagination=0): Response
    {
        $cds= $br->getPaginatedCD($pagination,9);
        return $this->render('cd_browser/index.html.twig', [
            'controller_name' => 'CDBrowserController',
            'browseType'=>"document",
            "cds"=>$cds,
            "currentPage"=>$pagination
        ]);
    }
    /**
     * @Route("/artists", name="byArtists")
     */
    public function browseByArtist(ArtistRepository $ar): Response
    {
        $artists = $ar-> findAllEntityArtistAlphabetical(CD::class);
        return $this->render('cd_browser/index.html.twig', [
            'controller_name' => 'CDBrowserController',
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
    public function searchResult(Request $request,CDRepository $br){

        $cds = $br->getCDWithString($request->request->get('form')['query']);
        return $this->render('cd_browser/index.html.twig', [
            'controller_name' => 'CDBrowserController',
            'cds'=>$cds,
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
    public function getArtistCD(int $id,CDRepository $br):Response{
        $cds = $br->getArtistsCD($id);
        $response = "<ul>";
        foreach($cds as $b){
            $response .= '<li> <a href="'.$this->generateUrl('cd_preview', ['id' => $b->getId()]).'">'.$b->getTitle().'</a></li>';
        }
        $response.="</ul>";
        return new Response($response);
    }
    
}
