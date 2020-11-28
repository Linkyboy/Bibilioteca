<?php

namespace App\Controller\Admin;

use App\Entity\CD;
use App\Form\CDType;
use App\Repository\CDRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/cd")
 */
class CDController extends AbstractController
{
    /**
     * @Route("/", name="c_d_index", methods={"GET"})
     */
    public function index(CDRepository $cDRepository): Response
    {
        return $this->render('cd/index.html.twig', [
            'c_ds' => $cDRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="c_d_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $cD = new CD();
        $form = $this->createForm(CDType::class, $cD);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($cD);
            $entityManager->flush();

            return $this->redirectToRoute('c_d_index');
        }

        return $this->render('cd/new.html.twig', [
            'c_d' => $cD,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="c_d_show", methods={"GET"})
     */
    public function show(CD $cD): Response
    {
        return $this->render('cd/show.html.twig', [
            'c_d' => $cD,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="c_d_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, CD $cD): Response
    {
        $form = $this->createForm(CDType::class, $cD);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('c_d_index');
        }

        return $this->render('cd/edit.html.twig', [
            'c_d' => $cD,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="c_d_delete", methods={"DELETE"})
     */
    public function delete(Request $request, CD $cD): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cD->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($cD);
            $entityManager->flush();
        }

        return $this->redirectToRoute('c_d_index');
    }
}
