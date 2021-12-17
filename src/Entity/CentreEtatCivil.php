<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CentreEtatCivilRepository")
 * @ORM\HasLifecycleCallbacks
 */
class CentreEtatCivil
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
    private $libelle;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $createdBy;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ActeDeces", mappedBy="centreEtatCivil")
     */
    private $acteDeces;

    /**
     * CallBack appelé à chaque fois que l'on veut ajouter un centre d'etat civil à la bd
     * 
     * @ORM\PrePersist
     * @ORM\PreUpdate
     *
     * @return void
     */
    public function PrePersist()
    {
        if (empty($this->createdAt)) {
            $this->createdAt = new \DateTime();
        }
    }

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\ManyToOne(targetEntity=Arrondissements::class, inversedBy="centreEtatCivils")
     */
    private $arrondissement;

    public function __construct()
    {
        $this->acteDeces = new ArrayCollection();
    }

    /**
     * Permet de générer un slug pour le centre d'état civil
     * 
     * @ORM\PrePersist
     * @ORM\PreUpdate
     *
     * @return String
     */
    public function initializeSlug()
    {
        if (empty($this->slug)) {
            $slugify = new Slugify();
            $this->slug = $slugify->slugify($this->libelle);
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCreatedBy(): ?string
    {
        return $this->createdBy;
    }

    public function setCreatedBy(string $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * @return Collection|ActeDeces[]

    public function getActeDeces(): Collection
    {
        return $this->acteDeces;
    }

    public function addActeDece(ActeDeces $acteDece): self
    {
        if (!$this->acteDeces->contains($acteDece)) {
            $this->acteDeces[] = $acteDece;
            $acteDece->setCentreEtatCivil($this);
        }

        return $this;
    }

    public function removeActeDece(ActeDeces $acteDece): self
    {
        if ($this->acteDeces->contains($acteDece)) {
            $this->acteDeces->removeElement($acteDece);
            // set the owning side to null (unless already changed)
            if ($acteDece->getCentreEtatCivil() === $this) {
                $acteDece->setCentreEtatCivil(null);
            }
        }

        return $this;
    }
     * */

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getArrondissement(): ?Arrondissements
    {
        return $this->arrondissement;
    }

    public function setArrondissement(?Arrondissements $arrondissement): self
    {
        $this->arrondissement = $arrondissement;

        return $this;
    }

    public function __toString()
    {
        // TODO: Implement __toString() method.
        return $this->libelle;
    }
}
