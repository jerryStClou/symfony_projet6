<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Vehicule;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class VehiculeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('brand', null, ['attr' => ['class' => 'styleCRUD']])
            ->add('model', null, ['attr' => ['class' => 'styleCRUD']])
            ->add('year', null, ['attr' => ['class' => 'styleCRUD']])
            ->add('price', NumberType::class, [
                'constraints' => [new Range([
                    'min' => 1,
                    'max' => 500,
                    'notInRangeMessage' => 'You price need to be between {{ min }}€ and {{ max }}€',
                ])],
                'attr' => ['class' => 'styleCRUD']
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'attr' => ['class' => 'styleCRUD']
            ])
            ->add('photo', FileType::class, [
                'multiple' => true,
                'mapped' => false,
                'required' => false,
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
