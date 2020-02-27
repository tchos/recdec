<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User
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
    private $fullName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $hash;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Equipe", inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     */
    private $equipe;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ActeDeces", mappedBy="agentSaisie")
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

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): self
    {
        $this->fullName = $fullName;

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

    public function getHash(): ?string
    {
        return $this->hash;
    }

    public function setHash(string $hash): self
    {
        $this->hash = $hash;

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

    public function getEquipe(): ?Equipe
    {
        return $this->equipe;
    }

    public function setEquipe(?Equipe $equipe): self
    {
        $this->equipe = $equipe;

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
            $acteDece->setAgentSaisie($this);
        }

        return $this;
    }

    public function removeActeDece(ActeDeces $acteDece): self
    {
        if ($this->acteDeces->contains($acteDece)) {
            $this->acteDeces->removeElement($acteDece);
            // set the owning side to null (unless already changed)
            if ($acteDece->getAgentSaisie() === $this) {
                $acteDece->setAgentSaisie(null);
            }
        }

        return $this;
    }
}
