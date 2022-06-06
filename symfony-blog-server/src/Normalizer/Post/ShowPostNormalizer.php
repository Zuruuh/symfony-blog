<?php

namespace App\Normalizer\Post;

use App\Common\Normalizer\AbstractListNormalizer;
use App\Entity\Post;

/**
 * @extends AbstractListNormalizer<Post>
 */
class ShowPostNormalizer extends AbstractListNormalizer
{
    public static function getDefaultContext(): array
    {
        return [
            'show_author' => true
        ];
    }

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