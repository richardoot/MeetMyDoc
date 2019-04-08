<?php

namespace App\Form;

use App\Entity\DossierPatient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DossierPatientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('allergies')
            ->add('maladiesGraves')
            ->add('vaccins')
            ->add('patient')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DossierPatient::class,
        ]);
    }
}
