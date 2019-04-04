<?php

namespace App\Form;

use App\Entity\Specialite;
use App\Entity\Medecin;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class Medecin1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('specialite', EntityType::class, array(
                'class' => Specialite::class,
                'choice_label' => 'nom',
                'multiple' => false,
                'expanded' => false,
            ))
            ->add('nom')
            ->add('ville')       
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Medecin::class,
        ]);
    }
}
