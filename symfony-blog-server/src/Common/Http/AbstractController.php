<?php

declare(strict_types=1);

namespace App\Common\Http;

use App\Common\Normalizer\AbstractNormalizer;
use App\Common\Paging\RequestOptionsExtractorInterface;
use App\Common\Paging\RequestOptionsExtractorTrait;
use App\Entity\User;
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
        protected readonly LoggerInterface        $logger,
        protected readonly TranslatorInterface    $translator,
        protected readonly EntityManagerInterface $em,
        protected readonly RequestStack           $requestStack
    ) {}

    /**
     * @template T of true|false
     * @param T $stringify
     * @phpstan-return (T is true ? string : array<string|int, mixed>)
     */
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
            $errors['children'][$child->getName()] = self::formatForm($child, false, true);
        }

        if ($stringify) {
            return json_encode(['errors' => $errors]) ?: '{}';
        }

        return $errors;
    }

    /**
     * @deprecated
     * @return string|array<string|int, mixed>
     */
    public static function form(FormInterface $form, bool $stringify = false, bool $recursive = false): string|array
    {
        return self::formatForm($form, $stringify, $recursive);
    }

    /** @param array<string, string> $headers */
    protected function displayForm(FormInterface $form, int $status = 400, array $headers = []): Response
    {
        $errors = self::formatForm($form);

        return new JsonResponse($errors, $status, $headers);
    }

    /**
     * @param array<string, mixed> $context
     *
     * @return array<string|int, mixed>
     */
    protected function serialize(string $normalizer, mixed $object, array $context = []): array
    {
        try {
            return AbstractNormalizer::serialize($normalizer, $object, 'json', $context) ?? [];
        } catch (\Throwable $_) {
            return [];
        }
    }

    /** @param array<string, mixed> $parameters */
    protected function t(string $id, array $parameters = [], string $domain = null, string $locale = null): string
    {
        return $this->translator->trans($id, $parameters, $domain, $locale);
    }

    protected function void(): Response
    {
        return new Response(null, 204);
    }

    protected function getRequest(): Request
    {
        return $this->requestStack->getMainRequest(); // @phpstan-ignore-line
    }

    protected function exception(int $statusCode, string $message): HttpException
    {
        return new HttpException($statusCode, $message);
    }

    protected function createAndSubmitForm(string $type, $data = null, array $options = []): FormInterface
    {
        return $this
            ->createForm($type, $data, $options)
            ->submit($this
                ->getRequest()
                ->request
                ->all()
            );
    }
    /**
     * @return User
     */
    protected function getUser()
    {
        $user = parent::getUser();

        if (!is_null($user) && !$user instanceof User) {
            throw new \LogicException('Invalid user was found (?)');
        }

        return $user; // @phpstan-ignore-line
    }

    /**
     * @param array<string, mixed> $context
     */
    protected function serializeToJson(string $normalizer, mixed $object, array $context = []): Response
    {
        $data = $this->serialize($normalizer, $object, $context);

        return $this->json($data);
    }
}
