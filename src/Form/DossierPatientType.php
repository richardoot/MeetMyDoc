<?php

namespace App\Form;

use App\Entity\DossierPatient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use App\Entity\GroupeSanguin;
use App\Entity\Allergie;
use App\Entity\MaladieGrave;
use App\Entity\Vaccin;


class DossierPatientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('groupeSanguin', EntityType::class,['class' => GroupeSanguin::class,
                                                  'choice_label' => 'nom',
                                                  'multiple' => false,
                                                  'expanded' => false])
            ->add('allergies', EntityType::class,['class' => Allergie::class,
                                                  'choice_label' => 'nom',
                                                  'multiple' => true,
                                                  'expanded' => true])
            ->add('maladiesGraves', EntityType::class,['class' => MaladieGrave::class,
                                                  'choice_label' => 'nom',
                                                  'multiple' => true,
                                                  'expanded' => true])
            ->add('vaccins', EntityType::class,['class' => Vaccin::class,
                                                  'choice_label' => 'nom',
                                                  'multiple' => true,
                                                  'expanded' => true])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DossierPatient::class,
        ]);
    }
}
