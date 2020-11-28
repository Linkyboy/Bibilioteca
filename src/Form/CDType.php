<?php

namespace App\Form;

use App\Entity\CD;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CDType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('referenceNumber')
            ->add('title')
            ->add('publicationDate')
            ->add('inCollectionDate')
            ->add('availability')
            ->add('publisher')
            ->add('isPinned')
            ->add('description')
            ->add('illustration')
            ->add('totalDuration')
            ->add('category')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CD::class,
        ]);
    }
}
