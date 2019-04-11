<?php

namespace App\Form;

use App\Entity\RessourceDossierPatient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class ModifDossierPatientParMedecinType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('emails', CollectionType::class, [
                        // each entry in the array will be an "email" field
                      'entry_type' => RessourceDossierPatientType::class,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RessourceDossierPatient::class,
        ]);
    }
}
