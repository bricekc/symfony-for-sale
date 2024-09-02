<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Rollerworks\Component\PasswordStrength\Validator\Constraints as RollerworksPassword;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', TextareaType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrez un mail',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Le mail doit contenir au moins {{ limit }} caractères',
                        'max' => 50,
                    ]),
                ],
            ])
            ->add('firstname', TextareaType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrez un prénom',
                    ]),
                    new Length([
                        'min' => 3,
                        'minMessage' => 'Le prénom doit contenir au moins {{ limit }} caractères',
                        'max' => 20,
                    ]),
                ],
            ])
            ->add('lastname', TextareaType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrez un nom de famille',
                    ]),
                    new Length([
                        'min' => 3,
                        'minMessage' => 'Le nom doit contenir au moins {{ limit }} caractères',
                        'max' => 20,
                    ]),
                ],
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options' => ['label' => 'Password'],
                'second_options' => ['label' => 'Repeat Password'],
                'constraints' => [
                    new RollerworksPassword\PasswordStrength([
                        'minStrength' => 4,
                        'message' => 'Le mot de passe doit contenir au moins 10 caractères, une lettre minuscule, une lettre majuscule, un chiffre et un caractère spécial.',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
