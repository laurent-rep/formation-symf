<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert; // Pour utiliser les validations de formulaire
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity; // Pour vérifier que l'entity est unique

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\HasLifecycleCallbacks()
 * Unique Entity prend deux paramètres : les champs et le message
 * Ici l'email doit être unique
 * @UniqueEntity(
 *     fields={"email"},
 *     message="Cet utilisateur existe déjà"
 * )
 */
// Comme un User doit pouvoir se connecter => On l'ajoute dans le security.yaml
class User implements UserInterface // UserInterface => Nécéssaire à TOUS les utilisateurs
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Vous devez renseigner votre prénom")
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Vous devez renseigner votre nom de famille")
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email(message="Veuillez renseignez un email valide")
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Url(message="URL d'avatar non valide")
     */
    private $avatar;

    /**
     * @ORM\Column(type="string", length=255)
     *
     */
    private $hash;


    /**
     * @Assert\EqualTo(propertyPath="hash", message="Les deux mots de passes ne correspondent pas")
     */
    public $passwordConfirm;
    // On met en public pour ne pas à avoir à créer let getters et setters
    // Pas d'annotations non plus car ce n'est pas qqch qui existe dans la BDD
    // Sauf Assert pour vérifier EqualTo


    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min="10",minMessage="Votre introduction doit faire au moins 10 caractères")
     */
    private $introduction;

    /**
     * @ORM\Column(type="text")
     * @Assert\Length(min="100",minMessage="Votre description doit faire au moins 100 caractères")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Annonce", mappedBy="author")
     */
    private $annonces;


    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function initSlug()
    {
        // Permet de générer un slug automatiquement
        if (empty($this->slug)) {
            // nouvelle classe slugify
            $slugify = new Slugify();
            // On dit que le slug = slugify(ce que je veux)
            $this->slug = $slugify->slugify($this->firstName . '' . $this->lastName);
        }

    }

    // Pour que dans Twig nous n'avons pas à faire {{ annonce.author.firstname }} {{ annone.author.lastname }}
    public function getFullName()
    {
        return "{$this->firstName} {$this->lastName}";
    }

    public function __construct()
    {
        $this->annonces = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getHash(): ?string
    {
        return $this->hash;
    }

    public function setHash(string $hash): self
    {
        $this->hash = $hash;

        return $this;
    }

    public function getIntroduction(): ?string
    {
        return $this->introduction;
    }

    public function setIntroduction(string $introduction): self
    {
        $this->introduction = $introduction;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection|Annonce[]
     */
    public function getAnnonces(): Collection
    {
        return $this->annonces;
    }

    public function addAnnonce(Annonce $annonce): self
    {
        if (!$this->annonces->contains($annonce)) {
            $this->annonces[] = $annonce;
            $annonce->setAuthor($this);
        }

        return $this;
    }

    public function removeAnnonce(Annonce $annonce): self
    {
        if ($this->annonces->contains($annonce)) {
            $this->annonces->removeElement($annonce);
            // set the owning side to null (unless already changed)
            if ($annonce->getAuthor() === $this) {
                $annonce->setAuthor(null);
            }
        }

        return $this;
    }


    // Methodes necessaires à l'implémentation de UserInterface

    public function getRoles()
    {
        return ['ROLE_USER']; // Doit retourner un tabler de chaine de caractère (ce que l'utilisateur peut faire)
    }

    public function getPassword()
    {
        return $this->hash;
    }


    public function getSalt()
    {
    } // Pas besoin le salt est déjà dedans!


    public function getUsername()
    {
        return $this->email; // Renvoie l'email car on veut que le User se connecte avec son email
    }

    public function eraseCredentials()
    {
    } // Supprime des données sensibles de l'utilisateurs dans le code (Nous passons par la BDD et encodés)

}
