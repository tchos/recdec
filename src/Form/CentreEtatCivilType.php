<?php

namespace App\Form;

use App\Entity\Arrondissements;
use App\Entity\CentreEtatCivil;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CentreEtatCivilType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('libelle', TextType::class,
                $this->getConfiguration("Nom du centre d'état civil", "Ex: CEC DE YAOUNDE I"))
            ->add('arrondissement', EntityType::class, [
                'class' => Arrondissements::class,
                "choice_label" => "libelleArrondissement"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CentreEtatCivil::class,
        ]);
    }
}
