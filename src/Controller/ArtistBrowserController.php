<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ArtistRepository;
use App\Repository\CategoryRepository;
use App\Repository\UserRepository;
use App\Entity\Category;
use App\Manager\UserPreferences;
class ArtistBrowserController extends AbstractController
{
    /**
     * @Route("/artistbrowser/all/{letter}", name="artist_browser")
     */
    public function index(ArtistRepository $ar,string $letter='a'): Response
    {
       
        $artists = $ar->getArtistStartingByLetter($letter);
        return $this->render('artist_browser/index.html.twig', [
            'controller_name' => 'ArtistBrowserController',
            "artists"=>$artists,
            "browseType"=>"artist",
            "alphabet"=>['a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z'],
            'currentLetter'=>$letter
        ]);
    }
    /**
     * @Route("/artistbrowser/category", name="artist_browserCategory")
     */
    public function getCategories(CategoryRepository $cr):Response{
        $categories = $cr->findAllCateogoriesWithArtist();
        return $this->render('artist_browser/index.html.twig', [
            'controller_name' => 'ArtistBrowserController',
            "categories"=>$categories,
            "browseType"=>"categories",
        ]);
    }
        
    /**
     * renderArtistInCategory
     *
     * @param  mixed $categoryID
     * @param  mixed $ar
     * @param  mixed $r
     * @return void
     * @todo make the return
     */
    public function renderArtistInCategory(int $categoryID,ArtistRepository $ar,Response $r){
        $artistes = $ar->getArtistInCategory($categoryID);
        $htmlCode = "";
        foreach($artistes as $a){
            
        }
    }
}
