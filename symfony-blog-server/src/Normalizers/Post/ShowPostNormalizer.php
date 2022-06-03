<?php

namespace App\Normalizers\Post;

use App\Common\AbstractNormalizer;
use App\Entity\Post;

class ShowPostNormalizer extends AbstractNormalizer
{
    public static function getDefaultContext(): array
    {
        return [
            'show_author' => true
        ];
    }

    /**
     * @param Post $object
     */
    public function normalize($object, string $format = null, array $context = []): array
    {
        $context = $this->getDefaults($context);

        $data = [
            'id' => $object->getId(),
            'slug' => $object->getSlug(),
            'title' => $object->getTitle(),
            'content' => $object->getContent(),
        ];

        if ($context['show_author']) {
            $data['author'] = (new AuthorNormalizer())->normalize($object->getAuthor(), $format, $context);
        }

        return $data;
    }

    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return $data instanceof Post;
    }
}