<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CategoryFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('name', TextType::class, [
            'label' => "Nom *",
            'attr' => ['placeholder' => "nom de la catégorie", 'class' => "form-control my-1"],
            'constraints' => [
                new NotBlank(['message' => "Merci de saisir votre prénom"]),
            ],
            'required' => true,
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
            'data_class' => Category::class,
        ]);
    }
}
