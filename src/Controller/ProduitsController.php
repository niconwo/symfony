<?php

namespace App\Controller;

use App\Entity\Produits;
use App\Form\Produits1Type;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/produits")
 */
class ProduitsController extends AbstractController
{
    /**
     * @Route("/", name="produits_index", methods="GET")
     */
    public function index(): Response
    {
        $produits = $this->getDoctrine()
            ->getRepository(Produits::class)
            ->findAll();

        return $this->render('produits/index.html.twig', ['produits' => $produits]);
    }

    /**
     * @Route("/new", name="produits_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $produit = new Produits();
        $form = $this->createForm(Produits1Type::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($produit);
            $em->flush();

            return $this->redirectToRoute('produits_index');
        }

        return $this->render('produits/new.html.twig', [
            'produit' => $produit,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="produits_show", methods="GET")
     */
    public function show(Produits $produit): Response
    {
        return $this->render('produits/show.html.twig', ['produit' => $produit]);
    }

    /**
     * @Route("/{id}/edit", name="produits_edit", methods="GET|POST")
     */
    public function edit(Request $request, Produits $produit): Response
    {
        $form = $this->createForm(Produits1Type::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('produits_edit', ['id' => $produit->getId()]);
        }

        return $this->render('produits/edit.html.twig', [
            'produit' => $produit,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="produits_delete", methods="DELETE")
     */
    public function delete(Request $request, Produits $produit): Response
    {
        if ($this->isCsrfTokenValid('delete'.$produit->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($produit);
            $em->flush();
        }

        return $this->redirectToRoute('produits_index');
    }
}
