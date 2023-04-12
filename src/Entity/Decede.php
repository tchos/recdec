<?php

namespace App\Entity;

use App\Repository\DecedeRepository;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use http\Message;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=DecedeRepository::class)
 * @ORM\HasLifecycleCallbacks
 * @UniqueEntity(fields = {"cni"},
 *      message = "Décédé dejà déjà enregistré en BD")
 */
class Decede
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length (min=4, minMessage="Au moins 4 caractères doivent être saisis")
     * @Assert\Regex(pattern="#[A-Z]+#", match=true,
     *     message="Bien vouloir saisir en majuscule, sans accents et sans caractères spéciaux !")
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $sexe;

    /**
     * @ORM\Column(type="date", nullable=true)
     *
     */
    private $naissance;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Assert\GreaterThanOrEqual(propertyPath="dateDeces",
     *     message="La date de décès doit être antérieur ou égale à la date d'entrée à la morgue")
     */
    private $dateEntreeMorgue;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lieuDeces;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Assert\GreaterThan(propertyPath="dateEntreeMorgue",
     *     message="La date d'entrée à la morgue doit être antérieure à la date de sortie de la morgue")
     */
    private $dateSortieMorgue;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Regex(pattern="#[A-Z]+#", match=true,
     *     message="Bien vouloir saisir en majuscule, sans accents et sans caractères spéciaux !")
     */
    private $profession;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Regex(pattern="#[A-Z]+#", match=true,
     *     message="Bien vouloir saisir en majuscule, sans accents et sans caractères spéciaux !")
     */
    private $lieuInhumation;

    /**
     * @ORM\Column(type="string", length=32, nullable=true)
     * @Assert\Regex(pattern="#([A-Z])+#", match=true,
     *     message="Bien vouloir saisir en majuscule, sans accents et sans caractères spéciaux !")
     */
    private $ville;

    /**
     * @ORM\Column(type="string", length=32, nullable=true)
     */
    private $region;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Regex(pattern="#[A-Z]+#", match=true,
     *     message="Bien vouloir saisir en majuscule, sans accents et sans caractères spéciaux !")
     */
    private $fosa;

    /**
     * @ORM\ManyToOne(targetEntity=Equipe::class, inversedBy="decedes")
     */
    private $equipe;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="decedes")
     */
    private $agentSaisie;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Assert\GreaterThanOrEqual ("-8 years", message="On ne saisit que les informations sur les décédés après 2015")
     */
    private $dateDeces;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateSaisie;

    /**
     * CallBack appelé à chaque fois que l'on veut enregistrer un acte de naissance pour
     * calculer automatiquement la date de saisie, le slug et l'âge du décédé.     *
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     *
     * @return void
     */
    public function PrePersist()
    {
        if (empty($this->dateSaisie)) {
            $this->dateSaisie = new \DateTime();
        }
        if (empty($this->age)) {
            //age = dateDeces - dateNaissance
            $this->age = $this->naissance->diff($this->dateDeces)->format('%y');
        }
    }

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $age;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(string $sexe): self
    {
        $this->sexe = $sexe;

        return $this;
    }

    public function getNaissance(): ?\DateTimeInterface
    {
        return $this->naissance;
    }

    public function setNaissance(?\DateTimeInterface $naissance): self
    {
        $this->naissance = $naissance;

        return $this;
    }

    public function getDateEntreeMorgue(): ?\DateTimeInterface
    {
        return $this->dateEntreeMorgue;
    }

    public function setDateEntreeMorgue(?\DateTimeInterface $dateEntreeMorgue): self
    {
        $this->dateEntreeMorgue = $dateEntreeMorgue;

        return $this;
    }

    public function getLieuDeces(): ?string
    {
        return $this->lieuDeces;
    }

    public function setLieuDeces(?string $lieuDeces): self
    {
        $this->lieuDeces = $lieuDeces;

        return $this;
    }

    public function getDateSortieMorgue(): ?\DateTimeInterface
    {
        return $this->dateSortieMorgue;
    }

    public function setDateSortieMorgue(?\DateTimeInterface $dateSortieMorgue): self
    {
        $this->dateSortieMorgue = $dateSortieMorgue;

        return $this;
    }

    public function getProfession(): ?string
    {
        return $this->profession;
    }

    public function setProfession(?string $profession): self
    {
        $this->profession = $profession;

        return $this;
    }

    public function getLieuInhumation(): ?string
    {
        return $this->lieuInhumation;
    }

    public function setLieuInhumation(?string $lieuInhumation): self
    {
        $this->lieuInhumation = $lieuInhumation;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(?string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function setRegion(?string $region): self
    {
        $this->region = $region;

        return $this;
    }

    public function getFosa(): ?string
    {
        return $this->fosa;
    }

    public function setFosa(string $fosa): self
    {
        $this->fosa = $fosa;

        return $this;
    }

    public function getEquipe(): ?Equipe
    {
        return $this->equipe;
    }

    public function setEquipe(?Equipe $equipe): self
    {
        $this->equipe = $equipe;

        return $this;
    }

    public function getAgentSaisie(): ?User
    {
        return $this->agentSaisie;
    }

    public function setAgentSaisie(?User $agentSaisie): self
    {
        $this->agentSaisie = $agentSaisie;

        return $this;
    }

    public function getDateDeces(): ?\DateTimeInterface
    {
        return $this->dateDeces;
    }

    public function setDateDeces(?\DateTimeInterface $dateDeces): self
    {
        $this->dateDeces = $dateDeces;

        return $this;
    }

    public function getDateSaisie(): ?\DateTimeInterface
    {
        return $this->dateSaisie;
    }

    public function setDateSaisie(\DateTimeInterface $dateSaisie): self
    {
        $this->dateSaisie = $dateSaisie;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(?int $age): self
    {
        $this->age = $age;

        return $this;
    }
}

/**
 * Assert\LessThanOrEqual("-18 years",message="Le décédé doit avoir au moins 18 ans")
 * Assert\Regex(pattern="/[A-Z]+/", message='Vous devez saisir en majuscule, sans accents et sans caractères spéciaux')
 * Assert\GreaterThanOrEqual (propertyPath="dateDeces",message="La date d'entrée à la morgue doit être antérieur à la date de sortie de la morgue")
 */
