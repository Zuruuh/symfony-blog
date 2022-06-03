<?php

namespace App\Normalizers\Post;

use App\Common\AbstractNormalizer;
use App\Entity\User;

class AuthorNormalizer extends AbstractNormalizer
{
    public static function getDefaultContext(): array
    {
        return [];
    }

    /**
     * @param User $object
     */
    public function normalize($object, string $format = null, array $context = []): array
    {
        return [
            'id' => $object->getId(),
            'username' => $object->getUsername()
        ];
    }

    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return $data instanceof User;
    }
}