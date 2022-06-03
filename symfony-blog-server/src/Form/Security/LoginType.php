<?php

namespace App\Form\Security;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints;

class LoginType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('usernameOrEmail',  null, [
                'constraints' => [
                    new Constraints\NotBlank(message: 'common.not_blank')
                ],
            ])
            ->add('password')
        ;
    }
}
