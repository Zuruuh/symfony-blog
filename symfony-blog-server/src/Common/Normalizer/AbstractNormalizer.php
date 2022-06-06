<?php

namespace App\Common\Normalizer;

use App\Normalizer\Exception\InvalidNormalizerProvidedException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;

/**
 * @template T
 */
abstract class AbstractNormalizer implements ContextAwareNormalizerInterface
{
    public abstract static function getDefaultContext(): array;

    /**
     * @param T $object
     *
     * @throws InvalidNormalizerProvidedException
     * @throws ExceptionInterface
     */
    public static function serialize(string $normalizer, mixed $object, ?string $format = null, array $context = []): ?array
    {
        $normalizer = new $normalizer();
        if (!$normalizer instanceof AbstractNormalizer) {
            throw new InvalidNormalizerProvidedException($normalizer);
        }

        if ($normalizer instanceof AbstractListNormalizer) {
            $objects = is_array($object) ? $object : [$object];

            return array_map(fn (mixed $data) => $normalizer->normalize($data, $format, $context), $objects);
        }

        return $normalizer->normalize($object, $format, $context);
    }

    protected function getDefaults(array $context = []): array
    {
        $defaults = $this::getDefaultContext();
        if ($this instanceof AbstractListNormalizer) {
            $defaults = ['multiple' => true, ...$defaults];
        }

        return [...$defaults, ...$context];
    }
}
