<?php

namespace App\Normalizers\User;

use App\Common\AbstractNormalizer;
use App\Entity\User;

class SelfNormalizer extends AbstractNormalizer
{
    public static function getDefaultContext(): array
    {
        return [];
    }

    /**
     * @param User $object
     */
    public function normalize($object, string $format = null, array $context = [])
    {
        return [
            'id' => $object->getId(),
            'username' => $object->getUsername(),
            'email' => $object->getEmail(),
            'roles' => $object->getRoles(),
        ];
    }

    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return $data instanceof User;
    }
}