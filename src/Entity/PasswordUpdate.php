<?php

namespace App\Entity;
use Symfony\Component\Validator\Constraints as Assert;

class PasswordUpdate
{
    private $oldPassword;

    /**
     * @Assert\Length(min=5, minMessage="Votre mode passe doit avoir au minimun 5 caractères !")
     */
    private $newPassword;

    /**
     * @Assert\EqualTo(propertyPath="newPassword", 
     * message="Les mots de passe que vous avez entrés ne correspondent pas !")
     */
    private $confirmNewPassword;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOldPassword(): ?string
    {
        return $this->oldPassword;
    }

    public function setOldPassword(string $oldPassword): self
    {
        $this->oldPassword = $oldPassword;

        return $this;
    }

    public function getNewPassword(): ?string
    {
        return $this->newPassword;
    }

    public function setNewPassword(string $newPassword): self
    {
        $this->newPassword = $newPassword;

        return $this;
    }

    public function getConfirmNewPassword(): ?string
    {
        return $this->confirmNewPassword;
    }

    public function setConfirmNewPassword(string $confirmNewPassword): self
    {
        $this->confirmNewPassword = $confirmNewPassword;

        return $this;
    }
}
