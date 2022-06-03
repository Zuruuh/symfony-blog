<?php

namespace App\Common;

use App\Normalizers\Exceptions\InvalidNormalizerProvidedException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;

abstract class AbstractNormalizer implements ContextAwareNormalizerInterface
{
    public abstract static function getDefaultContext(): array;

    /**
     * @throws InvalidNormalizerProvidedException
     * @throws ExceptionInterface
     */
    public static function serialize(string $normalizer, mixed $object, ?string $format = null, array $context = []): ?array
    {
        $normalizer = new $normalizer();
        if (!$normalizer instanceof AbstractNormalizer) {
            throw new InvalidNormalizerProvidedException($normalizer);
        }

        return $normalizer->normalize($object, $format, $context);
    }

    protected function getDefaults(array $context = []): array
    {
        return array_merge($this::getDefaultContext(), $context);
    }
}
