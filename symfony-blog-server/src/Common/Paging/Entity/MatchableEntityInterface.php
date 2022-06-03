<?php

namespace App\Common\Paging\Entity;

interface MatchableEntityInterface
{
    /**
     * @return array{string: string}
     */
    static function getMatching(): array;

    /**
     * @return array{"range": string[], "list": string[]}
     */
    static function getMatchingMeta(): array;
}
