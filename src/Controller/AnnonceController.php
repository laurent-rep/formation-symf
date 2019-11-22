<?php

namespace App\Controller;

use App\Repository\AnnonceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class AnnonceController extends AbstractController
{
    /**
     * @Route("/annonces", name="annonces_index")
     * @param AnnonceRepository $monRepo
     * @param SessionInterface $session
     * @return Response
     */
    public function index(AnnonceRepository $monRepo, SessionInterface $session)
    {

        dump($session);

        $annonces = $monRepo->findAll();

        return $this->render('annonce/index.html.twig', [
            'annonce' => $annonces,
        ]);
    }
}
