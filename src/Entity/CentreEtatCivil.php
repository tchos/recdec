<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CentreEtatCivilRepository")
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

    public function __construct()
    {
        $this->acteDeces = new ArrayCollection();
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
     */
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
}
