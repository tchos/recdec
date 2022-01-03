<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ActeDecesRepository")
 * @ORM\HasLifecycleCallbacks
 * 
 * Le nom du décédé doit être unique
 * @UniqueEntity(
 *      fields = {"fullName", "numeroActe"},
 *      message = "Un autre décédé porte déjà le même nom, merci de le modifier."
 * )
 */
class ActeDeces
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $centreEtatCivil;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $numeroActe;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $fullName;

    /**
     * @ORM\Column(type="date")
     * @Assert\GreaterThan(propertyPath="dateNaissance", 
     *  message="La date de décès ne peut être antérieur à la date de naissance !")
     * @Assert\GreaterThan ("-6 years",
     *     message="On ne saisit que les actes dont la date de décès est antérieure à 2015")
     */
    private $dateDeces;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lieuDeces;

    /**
     * @ORM\Column(type="integer")
     * @Assert\GreaterThanOrEqual(value=18,
     *     message="Le décédé doit avoir au moins 18 ans pour que son acte soit enregistré")
     */
    private $age;

    /**
     * @ORM\Column(type="date")
     * @Assert\LessThanOrEqual ("-18 years",
     *     message="Le décédé doit avoir au moins 18 ans pour que son acte soit enregistré")
     */
    private $dateNaissance;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lieuNaissance;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $profession;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $domicile;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $declarant;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\GreaterThan(propertyPath="dateActe", 
     *  message="La date de saisie ne peut être antérieur à la date de signature de l'acte !")
     */
    private $dateSaisie;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="acteDeces")
     * @ORM\JoinColumn(nullable=false)
     */
    private $agentSaisie;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

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
            $this->age = $this->dateNaissance->diff($this->dateDeces)->format('%y');
        }
        if (empty($this->slug)) {
            $slugify = new Slugify();
            $this->slug = $slugify->slugify($this->fullName);
        }
    }

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Assert\GreaterThan(propertyPath="dateDeces", 
     *  message="La date de signature de l'acte ne peut être antérieur à la date de décès !")
     */
    private $dateActe;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCentreEtatCivil(): ?string
    {
        return $this->centreEtatCivil;
    }

    public function setCentreEtatCivil(string $centreEtatCivil): self
    {
        $this->centreEtatCivil = $centreEtatCivil;

        return $this;
    }

    public function getNumeroActe(): ?string
    {
        return $this->numeroActe;
    }

    public function setNumeroActe(string $numeroActe): self
    {
        $this->numeroActe = $numeroActe;

        return $this;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): self
    {
        $this->fullName = $fullName;

        return $this;
    }

    public function getDateDeces(): ?\DateTimeInterface
    {
        return $this->dateDeces;
    }

    public function setDateDeces(\DateTimeInterface $dateDeces): self
    {
        $this->dateDeces = $dateDeces;

        return $this;
    }

    public function getLieuDeces(): ?string
    {
        return $this->lieuDeces;
    }

    public function setLieuDeces(string $lieuDeces): self
    {
        $this->lieuDeces = $lieuDeces;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(int $age): self
    {
        $this->age = $age;

        return $this;
    }

    public function getDateNaissance(): ?\DateTimeInterface
    {
        return $this->dateNaissance;
    }

    public function setDateNaissance(\DateTimeInterface $dateNaissance): self
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    public function getLieuNaissance(): ?string
    {
        return $this->lieuNaissance;
    }

    public function setLieuNaissance(string $lieuNaissance): self
    {
        $this->lieuNaissance = $lieuNaissance;

        return $this;
    }

    public function getProfession(): ?string
    {
        return $this->profession;
    }

    public function setProfession(string $profession): self
    {
        $this->profession = $profession;

        return $this;
    }

    public function getDomicile(): ?string
    {
        return $this->domicile;
    }

    public function setDomicile(string $domicile): self
    {
        $this->domicile = $domicile;

        return $this;
    }

    public function getDeclarant(): ?string
    {
        return $this->declarant;
    }

    public function setDeclarant(string $declarant): self
    {
        $this->declarant = $declarant;

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

    public function getAgentSaisie(): ?User
    {
        return $this->agentSaisie;
    }

    public function setAgentSaisie(?User $agentSaisie): self
    {
        $this->agentSaisie = $agentSaisie;

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

    public function getDateActe(): ?\DateTimeInterface
    {
        return $this->dateActe;
    }

    public function setDateActe(?\DateTimeInterface $dateActe): self
    {
        $this->dateActe = $dateActe;

        return $this;
    }

}
