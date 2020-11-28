<?php

namespace App\Form;

use App\Entity\Book;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookType extends AbstractType
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
            ->add('pages')
            ->add('originalLanguage')
            ->add('isbn')
            ->add('category')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
