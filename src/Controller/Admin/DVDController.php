<?php

namespace App\Controller\Admin;

use App\Entity\DVD;
use App\Form\DVDType;
use App\Repository\DVDRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/dvd")
 */
class DVDController extends AbstractController
{
    /**
     * @Route("/", name="d_v_d_index", methods={"GET"})
     */
    public function index(DVDRepository $dVDRepository): Response
    {
        return $this->render('dvd/index.html.twig', [
            'd_v_ds' => $dVDRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="d_v_d_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $dVD = new DVD();
        $form = $this->createForm(DVDType::class, $dVD);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($dVD);
            $entityManager->flush();

            return $this->redirectToRoute('d_v_d_index');
        }

        return $this->render('dvd/new.html.twig', [
            'd_v_d' => $dVD,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="d_v_d_show", methods={"GET"})
     */
    public function show(DVD $dVD): Response
    {
        return $this->render('dvd/show.html.twig', [
            'd_v_d' => $dVD,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="d_v_d_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, DVD $dVD): Response
    {
        $form = $this->createForm(DVDType::class, $dVD);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('d_v_d_index');
        }

        return $this->render('dvd/edit.html.twig', [
            'd_v_d' => $dVD,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="d_v_d_delete", methods={"DELETE"})
     */
    public function delete(Request $request, DVD $dVD): Response
    {
        if ($this->isCsrfTokenValid('delete'.$dVD->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($dVD);
            $entityManager->flush();
        }

        return $this->redirectToRoute('d_v_d_index');
    }
}
