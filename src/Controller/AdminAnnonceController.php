<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Form\AnnonceType;
use App\Repository\AnnonceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminAnnonceController extends AbstractController
{
    /**
     * A besoin du repository des annonces
     *
     * @Route("/admin/annonces", name="admin_annonces_index")
     * @param AnnonceRepository $repository
     * @return Response
     */
    public function index(AnnonceRepository $repository)
    {


        return $this->render('admin/annonce/index.html.twig', [
            // Ici on cherche toutes les annonces
            'annonces' => $repository->findAll()
        ]);
    }

    /**
     * Permet d'éditer l'annonce
     *
     * @Route("/admin/annonces/{id}/edit", name="admin_annonce_edit")
     *
     * @param Annonce $annonce
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function edit(Annonce $annonce, Request $request, EntityManagerInterface $manager)
    {
        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $manager->persist($annonce);
            $manager->flush();

            $this->addFlash(
                "success",
                "L'annonce à été modifiée"
            );

        }

        return $this->render("admin/annonce/edit.html.twig", [
            'annonce' => $annonce,
            'form' => $form->createView()
        ]);
    }


    /**
     * Permet de supprimer une annpnce
     *
     * @Route("/admin/annonces/{id}/delete", name="admin_annonce_delete")
     * @param Annonce $annonce
     * @param EntityManagerInterface $manager
     * @return RedirectResponse
     */
    public function delete(Annonce $annonce, EntityManagerInterface $manager)
    {

        if (count($annonce->getBookings()) > 0) {
            $this->addFlash(
                "warning",
                "Cette annonce à des réservations"
            );
        } else {
            $manager->remove($annonce);
            $manager->flush();

            $this->addFlash(
                "success",
                "L'annonce à été supprimée"
            );
        }


        return $this->redirectToRoute('admin_annonces_index');

    }
}
