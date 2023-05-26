<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ProductFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('category', EntityType::class, [
                'label' => "Catégorie *",
                'attr' => ['class' => "form-select my-1"],
                "class"=> Category::class,
                "choice_label"=>"name",
                "multiple"=>false,
                "expanded"=>false,
                'required' => true,
            ])
            ->add('name', TextType::class, [
                'label' => "Nom *",
                'attr' => ['placeholder' => "nom du produit", 'class' => "form-control my-1"],
                'constraints' => [
                    new NotBlank(['message' => "Merci de saisir un nom de produit"]),
                ],
                'required' => true,
            ])
            ->add('description', TextareaType::class, [
                'label' => "Description *",
                'attr' => ['placeholder' => "Description du produit", 'class' => "form-control my-1", 'rows' => 10],
                'required' => true,
            ])
            ->add('price', NumberType::class, [
                'label' => "Prix (€) *",
                'attr' => ['placeholder' => "42", 'class' => "form-control my-1", 'min' => 0],
                'scale' => 2,
                'required' => true,
            ])
            ->add('stock', IntegerType::class, [
                'label' => "Stock",
                'attr' => ['placeholder' => "42", 'class' => "form-control my-1", 'min' => 0],
                'required' => false,
            ])
            ->add('image', FileType::class, [
                'data_class' => null,
                'label' => "Choisir une photo *",
                'attr' => ['class' => "form-control my-1"],
                'required' => false,
                'mapped' => false,
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Valider",
                'attr' => ['class' => "w-100 btn btn-lg btn-success"],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
