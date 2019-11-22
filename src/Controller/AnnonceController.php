<?php

namespace App\Controller;

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
     * Permet d'afficher une seule annonce
     * @Route("/annonces/{slug}", name="annonce_show")
     * @param $slug
     * @param AnnonceRepository $repository
     * @return Response
     */
    public function show($slug, AnnonceRepository $repository)
    {
        // On récupère l'annonce qui correspond au slug
        $annonce = $repository->findOneBy($slug);

        return $this->render('annonce/show.html.twig');


    }

}
