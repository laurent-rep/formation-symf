<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Form\AnnonceType;
use App\Repository\AnnonceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnnonceController extends AbstractController
{

    /**
     * Affichage des annonces
     *
     * @Route("/annonces", name="annonces_index")
     * @param AnnonceRepository $monRepo
     * @return Response
     */
    public function index(AnnonceRepository $monRepo)
        // AnnonceRepository il nous sert à prendre des données dans la BDD par rapport à notre Entity (Injecté par référence)
    {
        // Prend tout ce qu'il trouve sur notre Entity
        $annonces = $monRepo->findAll();

        return $this->render('annonce/index.html.twig', [
            // On passe a twig tout ce qu'il y a dans la BDD sur notre Entity
            'annonce' => $annonces,
        ]);
    }


    /**
     * Permet de créer une annonce
     *
     * @Route("/annonces/new", name="annonce_create")
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface $manager)
        // Request : soumission de formulaire EntityManagerInterface : permet d'injecter un manager de doctrine
    {

        // On créé une nouvelle annonce
        $annonce = new Annonce();

        // Créé un formulaire de type AnnonceType, qu'on ajoute à l'annonce
        $form = $this->createForm(AnnonceType::class, $annonce);

        // Dit au Formulaire de gérer la requête envoyé par un bouton de type submit
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Si le formulaire est soumis et valide

            // Pour faire persister les images soumises dans le formulaire on parcours toutes les images
            foreach ($annonce->getImages() as $image) {
                // On dit a quelle annonce appartient l'image
                $image->setAnnonce($annonce);
                $manager->persist($image);

            }

            // Prendre en compte l'utilisateur de l'annonce !
            $annonce->setAuthor($this->getUser());

            // On fait persister notre entité entière avec ses images son titre etc
            $manager->persist($annonce);
            // On enregistre tout
            $manager->flush();

            // On ajoute un message flash
            $this->addFlash(
                'success',
                "L'annonce <strong>{$annonce->getTitle()}</strong> a bien enregistré !"
            );

            return $this->redirectToRoute('annonce_show', [
                // Redirection vers un route choisie (la notre a besoin d'un slug)
                'slug' => $annonce->getSlug()
            ]);
        }


        return $this->render('annonce/new.html.twig', [
            // On passe la vue du formulaire a twig
            'form' => $form->createView()
        ]);

    }

    /**
     * Permet d'afficher le formulaire d'édition
     *
     * @Route("/annonces/{slug}/edit", name="annonce_edit")
     * @param Annonce $annonce
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function edit(Annonce $annonce, Request $request, EntityManagerInterface $manager)
    {

        // Créé un formulaire de type AnnonceType, qu'on ajoute à l'annonce
        $form = $this->createForm(AnnonceType::class, $annonce);

        // Dit au Formulaire de gérer la requête envoyé par un bouton de type submit
        $form->handleRequest($request);


        // Si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {

            // Pour faire persister les images soumises dans le formulaire on parcours toutes les images
            foreach ($annonce->getImages() as $image) {
                // On dit a quelle annonce appartient l'image
                $image->setAnnonce($annonce);
                $manager->persist($image);

            }

            // On fait persister notre entité entière avec ses images son titre etc
            $manager->persist($annonce);
            // On enregistre tout
            $manager->flush();

            // On ajoute un message flash
            $this->addFlash(
                'success',
                "L'annonce <strong>{$annonce->getTitle()}</strong> a bien été modifiée !"
            );

            return $this->redirectToRoute('annonce_show', [
                // Redirection vers un route choisie (la notre a besoin d'un slug)
                'slug' => $annonce->getSlug()
            ]);
        }


        return $this->render("annonce/edit.html.twig", [
            'form' => $form->createView(),
            'annonce' => $annonce
        ]);

    }


    /**
     * Permet d'afficher une seule annonce
     * @Route("/annonces/{slug}", name="annonce_show") // Le {slug} est directement géré par notre Entity Annonce
     * @param Annonce $annonce
     * @return Response
     */
    public function show(Annonce $annonce)
        //Annonce est notre Entity on l'injecte par dépendance pour pouvoir la passer a twig directement
    {
        return $this->render('annonce/show.html.twig', [
            'annonce' => $annonce
        ]);


    }

}
