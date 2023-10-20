<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormEvent;
use App\Form\ProductImageType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('category', EntityType::class, [
                'class' => 'App\Entity\Category',
                'choice_label' => 'name',
                'placeholder' => 'Select category',
            ])
            ->add('subcategory', EntityType::class, [
                'class' => 'App\Entity\SubCategory',
                'choice_label' => 'name',
                'placeholder' => 'Select Sub category',
            ])
            ->add('name',TextType::class)
            ->add('price',TextType::class)
            ->add('status', ChoiceType::class, [
                'label' => 'Status',
                'choices' => [
                    'Active' => 1,
                    'Inactive' => 0,
                ],
                'placeholder' => 'Select Status',
                'expanded' => false,
                'multiple' => false,
            ])
            ->add('productImages', CollectionType::class, [
                'entry_type' => ProductImageType::class,
                'entry_options' => ['label' => false],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
