<?php

namespace App\Controller;

use App\Repository\DocumentRepository;
use App\Repository\PenaltyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserPageController extends AbstractController
{
    /**
     * @Route("/user/page", name="user_page")
     */
    public function index(PenaltyRepository $pr,DocumentRepository $dr): Response
    {
        $penalties = $pr->findHistoricUserPenalties($this->getUser()->getId(),"3 month");
        $currentBorrowings = $dr->getUserBorrow($this->getUser()->getId());

        return $this->render('user_page/index.html.twig', [
            "user"=>$this->getUser(),
            "penalties"=>$penalties,
            "borrowings"=>$currentBorrowings
        ]);
    }
}
