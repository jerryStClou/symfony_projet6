<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Vehicule;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VehiculeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('brand', null, ['attr' => ['class' => 'styleCRUD']])
            ->add('model', null, ['attr' => ['class' => 'styleCRUD']])
            ->add('year', null, ['attr' => ['class' => 'styleCRUD']])
            ->add('price', null, ['attr' => ['class' => 'styleCRUD']])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'attr' => ['class' => 'styleCRUD']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Vehicule::class,
        ]);
    }
}
