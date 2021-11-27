<?php

namespace App\Form;

use App\Entity\Arrondissements;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArrondissementsType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('libelleArrondissement', TextType::class,
            $this->getConfiguration("Nom de l'arrondissement", "Ex: YAOUNDE I"))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Arrondissements::class,
        ]);
    }
}
