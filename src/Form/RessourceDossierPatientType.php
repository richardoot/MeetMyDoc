<?php

namespace App\Form;

use App\Entity\RessourceDossierPatient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RessourceDossierPatientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('typeRessourceDossierPatient', EntityType::class,['class' => TypeRessourceDossierPatient::class,
                                                  'choice_label' => 'nom',
                                                  'multiple' => false,
                                                  'expanded' => true])
            ->add('urlRessource')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RessourceDossierPatient::class,
        ]);
    }
}
