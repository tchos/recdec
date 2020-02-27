<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\HasLifecycleCallbacks
 * 
 * L'email doit être unique chez chaque user
 * @UniqueEntity(
 *      fields = {"email"},
 *      message = "Un autre utilisateur s'est déjà inscrit avec cette adresse email, merci de la modifier."
 * )
 */
class User implements UserInterface
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

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Role", mappedBy="users")
     */
    private $userRoles;

    public function __construct()
    {
        $this->acteDeces = new ArrayCollection();
        $this->userRoles = new ArrayCollection();
    }

    /**
     * Permet de générer un slug pour le user
     * 
     * @ORM\PrePersist
     * @ORM\PreUpdate
     *
     */
    public function initializeSlug()
    {
        if (empty($this->slug)) {
            $slugify = new Slugify();
            $this->slug = $slugify->slugify($this->fullName);
        }
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

    /**
     * @return Collection|Role[]
     */
    public function getUserRoles(): Collection
    {
        return $this->userRoles;
    }

    public function addUserRole(Role $userRole): self
    {
        if (!$this->userRoles->contains($userRole)) {
            $this->userRoles[] = $userRole;
            $userRole->addUser($this);
        }

        return $this;
    }

    public function removeUserRole(Role $userRole): self
    {
        if ($this->userRoles->contains($userRole)) {
            $this->userRoles->removeElement($userRole);
            $userRole->removeUser($this);
        }

        return $this;
    }

    // Les fonction à implémenter pour l'interface UserInterface

    public function getRoles()
    {
        $roles = $this->userRoles->map(function ($role) {
            return $role->getTitle();
        })->toArray();

        $roles[] = 'ROLE_USER';

        return $roles;
    }

    public function getPassword()
    {
        return $this->hash;
    }

    public function getSalt(){}

    public function getUsername()
    {
        return $this->email;
    }

    public function eraseCredentials(){}
}
