<?php

namespace App\Entity;

use App\Repository\ArrondissementsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ArrondissementsRepository::class)
 */
class Arrondissements
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $libelleArrondissement;

    /**
     * @ORM\OneToMany(targetEntity=ActeDeces::class, mappedBy="arrondissement")
     */
    private $acteDeces;

    /**
     * @ORM\OneToMany(targetEntity=CentreEtatCivil::class, mappedBy="arrondissement")
     */
    private $centreEtatCivils;

    public function __construct()
    {
        $this->acteDeces = new ArrayCollection();
        $this->centreEtatCivils = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelleArrondissement(): ?string
    {
        return $this->libelleArrondissement;
    }

    public function setLibelleArrondissement(string $libelleArrondissement): self
    {
        $this->libelleArrondissement = $libelleArrondissement;

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
            $acteDece->setArrondissement($this);
        }

        return $this;
    }

    public function removeActeDece(ActeDeces $acteDece): self
    {
        if ($this->acteDeces->removeElement($acteDece)) {
            // set the owning side to null (unless already changed)
            if ($acteDece->getArrondissement() === $this) {
                $acteDece->setArrondissement(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|CentreEtatCivil[]
     */
    public function getCentreEtatCivils(): Collection
    {
        return $this->centreEtatCivils;
    }

    public function addCentreEtatCivil(CentreEtatCivil $centreEtatCivil): self
    {
        if (!$this->centreEtatCivils->contains($centreEtatCivil)) {
            $this->centreEtatCivils[] = $centreEtatCivil;
            $centreEtatCivil->setArrondissement($this);
        }

        return $this;
    }

    public function removeCentreEtatCivil(CentreEtatCivil $centreEtatCivil): self
    {
        if ($this->centreEtatCivils->removeElement($centreEtatCivil)) {
            // set the owning side to null (unless already changed)
            if ($centreEtatCivil->getArrondissement() === $this) {
                $centreEtatCivil->setArrondissement(null);
            }
        }

        return $this;
    }
}
