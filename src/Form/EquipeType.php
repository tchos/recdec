<?php

namespace App\Form;

use App\Entity\Equipe;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EquipeType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('libelle', TextType::class,
                $this->getConfiguration("Libellé de l'équipe", "Ex: EQUIPE 1"))
            ->add('responsable', TextType::class,
                $this->getConfiguration("Responsable de l'équipe", "Ex: TCHOS LOLO LE MILANAIS"))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Equipe::class,
        ]);
    }
}
