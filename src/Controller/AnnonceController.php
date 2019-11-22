<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Repository\AnnonceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnnonceController extends AbstractController
{
    /**
     * @Route("/annonces", name="annonces_index")
     * @param AnnonceRepository $monRepo
     * @return Response
     */
    public function index(AnnonceRepository $monRepo)
    {
        $annonces = $monRepo->findAll();


        return $this->render('annonce/index.html.twig', [
            'annonce' => $annonces,
        ]);
    }


    /**
     * @Route("/annonces/new", name="annonce_create")
     * @return Response
     */
    public function create()
    {
        return $this->render('annonce/new.html.twig');

    }


    /**
     * Permet d'afficher une seule annonce
     * @Route("/annonces/{slug}", name="annonce_show")
     * @param Annonce $annonce
     * @return Response
     */
    public function show(Annonce $annonce)
    {
        return $this->render('annonce/show.html.twig', [
            'annonce' => $annonce
        ]);



    }

}
