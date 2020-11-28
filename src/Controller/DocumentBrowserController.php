<?php

namespace App\Controller;

use App\Entity\CD;
use App\Entity\DVD;
use App\Entity\Book;
use App\Repository\CDRepository;
use App\Repository\DVDRepository;
use App\Repository\BookRepository;
use App\Repository\ArtistRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

    /**
     * @Route("/browser")
     */
class DocumentBrowserController extends AbstractController
{
    /**
     * @Route("/{docType}/all", name="browse_documents")
     */
    public function browseDocuments(string $docType,Request $request, PaginatorInterface $paginator, CDRepository $cr,DVDRepository $dr, BookRepository $br): Response
    {
        $result = null;
        switch($docType){
            case "books":
                $result = $br->findAll();
            break;
            case "dvd":
                $result = $dr->findAll();
            break;
            case "cd":
                $result = $cr->findAll();
            break;
        }
        $documents = $paginator->paginate(
            $result, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            6 // Nombre de résultats par page
        );
        dump($documents);
        return $this->render('document_browser/index.html.twig', [
            "documents"=>$documents,
            "docType"=>$docType,
            'browseType'=>"documents"
        ]);
    }
    /**
     * @Route("/{docType}/artists", name="document_by_artist")
     */
    public function browseByArtist(string $docType,ArtistRepository $ar): Response
    {
        $className = null;
        switch($docType){
            case "cd":
                $className=CD::class;
            break;
            case "books":
                $className = Book::class;
            break;
            case "dvd":
                $className = DVD::class;
            break;
        }
        
        $artists = $ar-> findAllEntityArtistAlphabetical($className);
        dump($artists);
        return $this->render('document_browser/index.html.twig', [
            'artists'=>$artists,
            'browseType'=>"artist",
            "docType"=>$docType
        ]);
    }
    /**
     * @Route("/search/",name="search",methods={"GET"})
     */
    public function searchResult(Request $request, BookRepository $br,CDRepository $cr,DVDRepository $dr)
    {
        $type = $request->query->get("form")['docType'];
        $result = null;
        switch($type){
            case "books":
                $result =  $br->getBookWithString($request->query->get("form")['query']);
            break;
            case "cd":
                $result =  $cr->getCDWithString($request->query->get('form')['query']);
            break;
            case "dvd":
                $result =  $cr->getDVDWithString($request->query->get('form')['query']);
            break;
            default:
                $type="unknown";
            break;
        }
        dump($type);
        return $this->render('document_browser/index.html.twig', [
            'documents'=>$result,
            'browseType'=>"documents",
            'docType'=>$type
        ]);
    }

    public function searchBar( string $docType)
    {
        $form = $this->createFormBuilder(null)
                ->setAction($this->generateUrl('search'))
                ->setMethod("GET")
                ->setAttribute("class","form-inline")
                ->add('docType', HiddenType::class, [
                    'data' => $docType,
                ])
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

    public function getArtistDocument(int $artistID, string $docType, BookRepository $br,CDRepository $cr, DVDRepository $dr)
    {
        $result = null;
        switch($docType){
            case "books":   
                $result = $br->getArtistsBook($artistID);
            break;
            case "cd":
                $result = $cr->getArtistsCD($artistID);
            break;
            case "dvd":
                $result = $dr->getArtistsDVD($artistID);
            break;
        }
        $response = "<ul>";
        foreach($result as $d){
            $response .= '<li> <a href="'.$this->generateUrl('document_preview', ['id' => $d->getId()]).'">'.$d->getTitle().'</a></li>';
        }
        $response.="</ul>";
        return new Response($response);
    }
}
