<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AddressFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('street', TextType::class, [
            'label' => "N° et Nom de rue *",
            'attr' => ['placeholder' => "1 rue machin", 'class' => "form-control my-1"],
            'required' => true,
        ])
        ->add('zip', TextType::class, [
            'label' => "Code postal *",
            'attr' => ['placeholder' => "75000", 'class' => "form-control my-1"],
            'required' => true,
        ])
        ->add('city', TextType::class, [
            'label' => "Ville *",
            'attr' => ['placeholder' => "Paris", 'class' => "form-control my-1"],
            'required' => true,
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
