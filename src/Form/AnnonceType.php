<?php

namespace App\Form;

use App\Entity\Annonce;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnnonceType extends AbstractType
{


    /**
     * POUR EVITER DE SE REPETER DANS LA MODIFICATION DES ATTRIBUTS ON CREER UNE FONCTION
     * @param $label
     * @param $placeholder
     * @return array
     */
    private function getConfig($label, $placeholder)
    {
        return [
            'label' => $label,
            'attr' => [
                'placeholder' => $placeholder
            ]
        ];
    }

    // FIN DE LA FONCTION

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // On ajoute chaque champs à notre builder (formulaire créé avec make:form)
            // Deuxième param : Type d'input, Troisième param: ['label' => $monlabel, 'attr' => ['placeholder'] => $monPlaceholder ]
            ->add('title', TextType::class, $this->getConfig("Titre", "Tapez un super titre pour votre annonce"))
            ->add('slug', TextType::class, $this->getConfig("Chaine URL", "Adresse web (automatique)"))
            ->add('coverImage', UrlType::class, $this->getConfig("Url de l'image principale", "Donnez l'addresse d'une image qui donne envie"))
            ->add('intro', TextType::class, $this->getConfig("Introduction", "Donnez une description globale"))
            ->add('content', TextareaType::class, $this->getConfig("Description détaillée", "Une description qui donne envie de venir chez vous"))
            ->add('rooms', IntegerType::class, $this->getConfig("Chambres", "Nombre de chambres disponibles"))
            ->add('price', MoneyType::class, $this->getConfig("Prix par nuit", "Indiquez le prix pour une nuit"));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Annonce::class,
        ]);
    }
}
