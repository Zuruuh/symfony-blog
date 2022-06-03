<?php

namespace App\Form\Security;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints;

class LoginAsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('usernameOrEmail', null, [
                'constraints' => [
                    new Constraints\NotBlank(message: 'common.not_blank'),
                ],
            ]);
    }
}