<?php

namespace App\Controller;
use App\Entity\Borrowing;
use App\Repository\BorrowingRepository;
use App\Repository\CopyRepository;
use App\Repository\DocumentRepository;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use DateTime;

class DocumentBorrowController extends AbstractController
{


    /**
     * @Route("/borrow/{id}", name="borrow")
     */
    public function index(int $id = null, DocumentRepository $dr): Response
    {
        
        $doc = $dr->findOneBy(["id"=>$id]);
        return $this->render('document_borrow/index.html.twig', [
            "verificationState"=>false,
            "document"=>$doc,
            "returnDate"=>(new DateTime())->modify("+7 days")
        ]);
        
    }

    /**
     * Undocumented function
     *
     * @Route("/registerBorrow", name="register_borrow")
     */
    public function registerBorrow(Request $request,BorrowingRepository $br,CopyRepository $cr){
        
        $id = $request->request->get('form')['id'];
        $copies = $cr->getAvailableCopies($id);
        $message = "";
        
        $this->denyAccessUnlessGranted('borrow', $id);
        $this->denyAccessUnlessGranted('penalty', $id);

        if(!isset($copies[0])){
            $message= "This document is not available";
        }
        else{
            $userId = $this->getUser()->getId();
            $manager = $this->getDoctrine()->getManager();
            $borrowing = new Borrowing();
            $borrowing->setCopy($copies[0]);
            $borrowing->setUser($this->getUser());
            $borrowing->setBorrowingDate(new DateTime());
            $borrowing->setExpectedReturnDate((new DateTime())->modify("+7 days"));
            $manager->persist($borrowing);
            $manager->flush();
            $message = "Your borrowing has been succesfully registered!";
        }
        
        return $this->render('document_borrow/index.html.twig', [
            "verificationState"=>true,
            "message"=>$message
            
        ]);
    }

    public function validationForm(int $id){
        $form = $this->createFormBuilder(null)
                ->setAction($this->generateUrl('register_borrow'))
                ->add('id', HiddenType::class,[
                    'data' => $id
                ])
                ->add("Yes",SubmitType::class,[
                    'attr'=>[
                        'class'=>'btn btn-success btn-block mt-1',
                        'style'=>'display:inline;'
                    ]
                ])
                ->getForm(); 
        return $this->render('validationBorrow.html.twig',[
            'form'=>$form->createView()
        ]);
    }
    /**
     * Undocumented function
     *@Route("/errorBorrow")
     * @return void
     */
    public function handleErrorOnBorrow(){
        return $this->render('document_borrow/errorDisplay.html.twig',[
            
        ]);
    }
}
