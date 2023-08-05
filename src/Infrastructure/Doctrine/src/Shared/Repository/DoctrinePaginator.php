<?php

declare(strict_types=1);

namespace Infrastructure\Doctrine\Shared\Repository;

use Doctrine\DBAL\Query\QueryBuilder;
use Domain\Shared\Repository\PaginatorInterface;
use InvalidArgumentException;

/**
 * @template T of object
 *
 * @implements PaginatorInterface<T>
 */
final readonly class DoctrinePaginator implements PaginatorInterface
{
    private int $firstResults;
    private int $maxResults;

    public function __construct(private QueryBuilder $queryBuilder)
    {
        $firstResults = $queryBuilder->getFirstResult();
        $maxResults = $queryBuilder->getMaxResults();

        if ($maxResults === null) {
            throw new InvalidArgumentException('Missing max results in query');
        }

        $this->firstResults = $firstResults;
        $this->maxResults = $maxResults;
    }

    public function getItemsPerPage(): int
    {
        return $this->maxResults;
    }

    public function getCurrentPage(): int
    {
        if (0 >= $this->maxResults) {
            return 1;
        }

        return (int) (1 + floor($this->firstResults / $this->maxResults));
    }

    public function getLastPage(): int
    {
        if (0 >= $this->maxResults) {
            return 1;
        }

        return (int) (ceil($this->getTotalItems() / $this->maxResults) ?: 1);
    }

    public function getTotalItems(): int
    {
        return $this->queryBuilder->executeQuery()->columnCount();
    }

    public function count(): int
    {
        return iterator_count($this->getIterator());
    }

    public function getIterator(): \Traversable
    {
        /**
         * @var \Traversable<positive-int, T>
         */
        return $this->queryBuilder->executeQuery()->iterateColumn();
    }
}
