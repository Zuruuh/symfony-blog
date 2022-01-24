<?php

namespace App\Util;

use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class FormGenerator
{
    public function __construct(
        private FormFactoryInterface $formFactory
    ) {
    }

    public function handleForm(string $formType, Request $request, mixed $data = null, array $options = []): FormInterface
    {
        $form = $this->formFactory->create($formType, $data, $options);
        $form->submit(json_decode($request->getContent(), true), true);

        $this->validateForm($form);

        return $form;
    }

    public function validateForm(FormInterface $form): void
    {
        $this->validateErrors($this->getFormErrors($form));
    }

    public function getFormErrors(FormInterface $form): array
    {
        $errorsIterable = $form->getErrors(true);
        $count = $errorsIterable->count();

        $errors = [];
        for ($i = 0; $i < $count; $i++) {
            $error = $errorsIterable->offsetGet($i);
            $errors[$error->getOrigin()->getName()] = $error->getMessage();
        }

        return $errors;
    }

    public function validateErrors(array $errors, bool $throwing = true): bool
    {
        if (empty($errors))
            return true;

        if ($throwing)
            throw new BadRequestHttpException(json_encode($errors));

        return false;
    }

    public function addFieldError(FormInterface $form, string $field, string $error): void
    {
        $formError = new FormError($error, null, [], null, $field);

        $form->get($field)->addError($formError);
    }
}
