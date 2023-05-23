<?php

namespace App\Form;

use App\Entity\Client;
use App\Form\AddressFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ClientRegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => "Prénom *",
                'attr' => ['placeholder' => "John", 'class' => "form-control my-1"],
                'constraints' => [
                    new NotBlank(['message' => "Merci de saisir votre prénom"]),
                ],
                'required' => true,
            ])
            ->add('lastname', TextType::class, [
                'label' => "Nom *",
                'attr' => ['placeholder' => "Doe", 'class' => "form-control my-1"],
                'constraints' => [
                    new NotBlank(['message' => "Merci de saisir votre nom"]),
                ],
                'required' => true,
            ])
            ->add('address', AddressFormType::class, [
                'mapped' => true,
                'label' => 'Adresse *',
                'constraints' => [
                    new NotBlank(['message' => "Merci de saisir votre adresse"]),
                ],
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
            'data_class' => Client::class,
        ]);
    }
}
