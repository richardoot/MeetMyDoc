<?php

namespace App\Form;

use App\Entity\TypeRessourceDossierPatient;
use App\Entity\DossierPatient;
use App\Entity\RessourceDossierPatient;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class RessourceDossierPatientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('typeRessourceDossierPatient', EntityType::class,['class' => TypeRessourceDossierPatient::class,
                                                  'choice_label' => 'nom',
                                                  'multiple' => false,
                                                  'expanded' => false])
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
