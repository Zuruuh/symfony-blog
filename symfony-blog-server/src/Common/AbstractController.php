<?php

declare(strict_types=1);

namespace App\Common;

use App\Common\Paging\RequestOptionsExtractorInterface;
use App\Common\Paging\RequestOptionsExtractorTrait;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as SymfonyController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Contracts\Translation\TranslatorInterface;

class AbstractController extends SymfonyController implements RequestOptionsExtractorInterface
{
    use RequestOptionsExtractorTrait;

    public function __construct(
        protected LoggerInterface $logger,
        protected TranslatorInterface $translator,
        protected EntityManagerInterface $em,
        protected RequestStack $requestStack
    ) {}

    public static function formatForm(FormInterface $form, bool $stringify = false, bool $recursive = false): string|array
    {
        $errors = $recursive ? [] : [
            'form' => [],
        ];

        // Global
        foreach ($form->getErrors() as $error) {
            if ($recursive) {
                $errors[] = $error->getMessage();
            } else {
                $errors['form'][] = $error->getMessage();
            }
        }

        /** @var FormInterface $child */
        foreach ($form as $child) {
            $errors['children'][$child->getName()] = self::form($child, false, true);
        }

        return $stringify ? json_encode(['errors' => $errors]) : $errors;
    }

    /**
     * @deprecated
     */
    public static function form(FormInterface $form, bool $stringify = false, bool $recursive = false): string|array
    {
        return self::formatForm($form, $stringify, $recursive);
    }

    protected function displayForm(FormInterface $form, int $status = 400, array $headers = []): Response
    {
        $errors = self::formatForm($form);

        return new JsonResponse($errors, $status);
    }

    protected function serialize(string $normalizer, mixed $object, array $context = []): array
    {
        try {
            return AbstractNormalizer::serialize($normalizer, $object, 'json', $context);
        } catch (\Exception $e) {
            return [];
        }
    }

    protected function t(?string $id, array $parameters = [], string $domain = null, string $locale = null): string
    {
        return $this->translator->trans($id, $parameters, $domain, $locale);
    }

    protected function void(): Response
    {
        return new Response(null, 204);
    }

    protected function getRequest(): Request
    {
        return $this->requestStack->getMainRequest();
    }

    protected function exception(int $statusCode, string $message): HttpException
    {
        return new HttpException($statusCode, $message);
    }
}
