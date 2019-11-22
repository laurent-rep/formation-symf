<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Form\AnnonceType;
use App\Repository\AnnonceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnnonceController extends AbstractController
{

    /**
     * @Route("/annonces", name="annonces_index")
     * @param AnnonceRepository $monRepo
     * @return Response
     */
    public function index(AnnonceRepository $monRepo) // AnnonceRepository il nous sert à prendre des données dans la BDD par rapport à notre Entity (Injecté par référence)
    {
        $annonces = $monRepo->findAll(); // Prend tout ce qu'il trouve sur notre Entity

        return $this->render('annonce/index.html.twig', [
            'annonce' => $annonces, // On passe a twig tout ce qu'il y a dans la BDD sur notre Entity
        ]);
    }



    /**
     * @Route("/annonces/new", name="annonce_create")
     * @param Request $request
     * @return Response
     */
    public function create(Request $request) // Request : soumission de formulaire
    {

        $annonce = new Annonce();
        $form = $this->createForm(AnnonceType::class, $annonce);

        $form->handleRequest($request); // Dit au Formulaire de gérer la requête

        if ($form->isSubmitted() && $form->isValid()) { // Si le formulaire est soumis et valide

            $manager = $this->getDoctrine()->getManager(); // On créé un manager pour insérer dans la BDD
            $manager->persist($annonce); // On fait persister notre entité
            $manager->flush();

            $this->addFlash( // On ajoute un message flash
                'success',
                "L'annonce <strong>{$annonce->getTitle()}</strong> a bien enregistré !"
            );

            return $this->redirectToRoute('annonce_show', [ // Redirection vers un route choisie (la notre a besoin d'un slug)
                'slug' => $annonce->getSlug()
            ]);
        }


        return $this->render('annonce/new.html.twig', [
            'form' => $form->createView() // On passe la vue du formulaire a twig
        ]);

    }


    /**
     * Permet d'afficher une seule annonce
     * @Route("/annonces/{slug}", name="annonce_show") // Le {slug} est directement géré par notre Entity Annonce
     * @param Annonce $annonce
     * @return Response
     */
    public function show(Annonce $annonce) //Annonce est notre Entity on l'injecte par dépendance pour pouvoir la passer a twig directement
    {
        return $this->render('annonce/show.html.twig', [
            'annonce' => $annonce
        ]);


    }

}
