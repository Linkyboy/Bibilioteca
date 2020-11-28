<?php

namespace App\Form;

use App\Entity\DVD;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DVDType extends AbstractType
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
            ->add('duration')
            ->add('hasBonus')
            ->add('category')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DVD::class,
        ]);
    }
}
