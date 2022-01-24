<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class LoginFormType extends AbstractType
{
    private const USERNAME_OR_EMAIL_NOT_BLANK_MESSAGE = 'You must enter your username or your email to login.';
    private const PASSWORD_NOT_BLANK_MESSAGE = 'You must enter your password to login.';

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('usernameOrEmail', TextType::class, [
                'required' => true,
                'empty_data' => '',
                'constraints' => [
                    new Assert\NotNull(message: self::USERNAME_OR_EMAIL_NOT_BLANK_MESSAGE),
                    new Assert\NotBlank(message: self::USERNAME_OR_EMAIL_NOT_BLANK_MESSAGE)
                ],
            ])
            ->add('password', TextType::class, [
                'required' => true,
                'empty_data' => '',
                'constraints' => [
                    new Assert\NotNull(message: self::PASSWORD_NOT_BLANK_MESSAGE),
                    new Assert\NotBlank(message: self::PASSWORD_NOT_BLANK_MESSAGE)
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null
        ]);
    }
}
