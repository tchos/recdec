<?php

namespace App\Form;

use App\Entity\Decede;
use App\Entity\Equipe;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DecedeType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class,
                $this->getConfiguration("Nom du(de la) défunt(e)", "Ex: TOUKO BENEDICTO PACIFICO"))
            ->add('sexe', ChoiceType::class, [
                'label' => 'Sexe',
                'choices' => [
                    'M' => 'M',
                    'F' => 'F'
                ]])
            ->add('naissance', DateType::class, [
                'label' => 'Date de naissance',
                'widget' => 'single_text',
            ])
            ->add('dateEntreeMorgue', DateType::class, [
                'label' => 'Date d\'entrée à la morgue',
                'widget' => 'single_text',
            ])
            ->add('dateDeces', DateType::class, [
                'label' => 'Date de décès',
                'widget' => 'single_text',
            ])
            ->add('lieuDeces', TextType::class,
                $this->getConfiguration("Lieu de décès", "Ex: TIGNERE"))
            ->add('dateSortieMorgue', DateType::class, [
                'label' => 'Date de sortie de la morgue',
                'widget' => 'single_text',
            ])
            ->add('profession', TextType::class,
                $this->getConfiguration("Profession du(de la) défunt(e)", "Ex: FONCTIONNAIRE"))
            ->add('lieuInhumation', TextType::class,
                $this->getConfiguration("Lieu d'inhumation", "Ex: GUIDIGUIS"))
            ->add('ville', TextType::class,
                $this->getConfiguration("Ville de la morgue", "Ex: TOMBEL"))
            ->add('region', ChoiceType::class, [
                'label' => 'Région ',
                        'choices' => [
                            'ADAMAOUA' => 'ADAMAOUA',
                            'CENTRE' => 'CENTRE',
                            'EST' => 'EST',
                            'EXTREME-NORD' => 'EXTREME-NORD',
                            'LITTORAL' => 'LITTORAL',
                            'NORD' => 'NORD',
                            'NORD-OUEST' => 'NORD-OUEST',
                            'OUEST' => 'OUEST',
                            'SUD' => 'SUD',
                            'SUD-OUEST' => 'SUS-OUEST',
            ]])
            ->add('fosa', TextType::class,
                $this->getConfiguration("Formation sanitaire", "Ex: HOPITAL REGIONAL DE BUEA"))
            ->add('equipe', EntityType::class, [
                'label' => 'Equipe ayant collecté l\'information',
                'class' => Equipe::class,
                'choice_label' => 'libelle'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Decede::class,
        ]);
    }
}
