<?php

namespace App\Controller;


use App\Entity\User;
use App\Form\RegisterType;
use App\Repository\CDRepository;
use App\Repository\DVDRepository;
use App\Repository\BookRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use DateTime;
    /**
     *  @Route("/home", name="home")
     */
class HomeController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(BookRepository $br,CDRepository $cr, DVDRepository $dr): Response
    {
        $lastBooks = $br->getEightLastBooks();
        $lastCDs = $cr->getEightLastCDs();
        $lastDVDs = $dr->getEightLastDVDs();
        return $this->render('home/index.html.twig', [
            'lastBooks'=>$lastBooks,
            'lastCDs'=>$lastCDs,
            'lastDVDs'=>$lastDVDs
        ]);
    }
    /**
     * @Route("/bookSelection", name="bookSelection")
     */
    public function bookSelection(BookRepository $br): Response
    {
        $books = $br->getPinnedBooks();
        return $this->render('home/bookSelection.html.twig', [
            'books'=>$books
        ]);
    }
    /**
     * @Route("/dvdSelection", name="dvdSelection")
     */
    public function dvdSelection(DVDRepository $dr): Response
    {
        $dvds = $dr->getPinnedDVDs();
        return $this->render('home/dvdSelection.html.twig', [
            'dvds'=>$dvds
        ]);
    }
    /**
     * @Route("/cdSelection", name="cdSelection")
     */
    public function cdSelection(CDRepository $cr): Response
    {
        $cds = $cr->getPinnedCDs();
        return $this->render('home/cdSelection.html.twig', [
            'cds'=>$cds
        ]);
    }

    /**
     * @Route("/register", name="register")
     */
    public function register(Request $request,UserPasswordEncoderInterface $encoder): Response
    {
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $user->setPassword($encoder->encodePassword(
                             $user,
                             $user->getPassword()
                         ));
            $user->setRegisterDate(new DateTime());    
            $user->setEndDate((new DateTime())->modify("+5 years"));    
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('homeindex');
        }

        return $this->render('home/registerPage.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
}
