<?php

namespace App\Form;

use App\Entity\Creneau;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class SupprimerCreneauType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //->add('etat')
            ->add('dateRDV', DateType::class)
            ->add('heureDebut', TimeType::class)
            ->add('heureFin', TimeType::class)
            ->add('duree', IntegerType::class, ['attr' => ['disabled' => 'disabled', 'value' => 30]])
            //->add('medecin')
            //->add('patient')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Creneau::class,
        ]);
    }
}
