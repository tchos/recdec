<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Equipe;
use App\form\ApplicationType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegistrationType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fullName', TextType::class, $this->getConfiguration("Noms et prénoms",
                "Ex: Bakwo Dipanda Bokengue" ))
            ->add('email', EmailType::class, $this->getConfiguration("Adresse Email", "Ex: bakwo@minfi.cm"))
            ->add('hash', PasswordType::class, $this->getConfiguration("Mot de passe", "Ex: minfi"))
            ->add('passwordConfirm', PasswordType::class, 
                $this->getConfiguration("Veuillez confirler votre mot de passe", "Ex: minfi"))
            ->add('equipe', EntityType::class, [
                'class' => Equipe::class,
                'choice_label' => 'libelle'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
