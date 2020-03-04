<?php

namespace App\Form;

use App\Entity\ActeDeces;
use App\form\ApplicationType;
use App\Entity\CentreEtatCivil;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ActeDecesType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('numeroActe', TextType::class, 
                $this->getConfiguration("Numéro de l'acte de dédès", "Ex:1234/YAOUNDE/2142"))

            ->add('fullName', TextType::class, 
                $this->getConfiguration("Noms et prénoms du décédé", "Ex: KWETTE NOUMSI"))

            ->add('dateDeces', DateType::class, ['widget' => 'single_text'])

            ->add('lieuDeces', TextType::class,
                $this->getConfiguration("Lieu de décès", "Ex: YAOUNDE"))

            ->add('dateNaissance', DateType::class, ['widget' => 'single_text'])

            ->add('lieuNaissance', TextType::class, 
                $this->getConfiguration("Lieu de naissance", "Ex: DOUALA"))

            ->add('profession', TextType::class, 
                $this->getConfiguration("Profession", "Ex: INFORMATICIEN"))

            ->add('domicile', TextType::class,
                $this->getConfiguration("Domicilié à", "Ex: YAOUNDE"))

            ->add('declarant', TextType::class, 
                $this->getConfiguration("Déclarant du décès", "Ex: KWETTE ELOHIM MELCHISEDEC"))

            ->add('dateActe', DateType::class, ['widget' => 'single_text'])
            
            ->add('centreEtatCivil', EntityType::class, [
                'class' => CentreEtatCivil::class,
                "choice_label" => "libelle"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ActeDeces::class,
        ]);
    }
}
