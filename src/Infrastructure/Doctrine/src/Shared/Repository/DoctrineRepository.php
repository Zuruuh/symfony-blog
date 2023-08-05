<?php

declare(strict_types=1);

namespace Infrastructure\Doctrine\Shared\Repository;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Domain\Shared\Repository\PaginatorInterface;
use Domain\Shared\Repository\RepositoryInterface;

/**
 * @template T of object
 *
 * @implements RepositoryInterface<T>
 */
abstract class DoctrineRepository implements RepositoryInterface
{
    private ?int $page = null;
    private ?int $itemsPerPage = null;

    private QueryBuilder $queryBuilder;

    public function __construct(protected readonly Connection $connection)
    {
        $this->queryBuilder = $connection->createQueryBuilder();
    }

    public function getIterator(): \Iterator
    {
        $paginator = $this->paginator();
        if ($paginator !== null) {
            yield from $paginator;
        } else {
            yield from $this->queryBuilder->executeQuery()->iterateColumn();
        }
    }

    public function count(): int
    {
        $paginator = $this->paginator();
        if ($paginator !== null) {
            return $paginator->count();
        }

        return (int) $this
            ->queryBuilder
            ->select('count(1)')
            ->executeQuery()
            ->columnCount()
        ;
    }

    public function paginator(): ?PaginatorInterface
    {
        if (in_array(null, [$this->page, $this->itemsPerPage], true)) {
            return null;
        }

        $firstResult = ($this->page - 1) * $this->itemsPerPage;
        $repository = $this->filter(
            static fn (QueryBuilder $qb) =>
            $qb->setFirstResult($firstResult)->setMaxResults($this->itemsPerPage)
        );

        $repository->queryBuilder;
    }

    public function withoutPagination(): static
    {
        $cloned = clone $this;
        $cloned->page = null;
        $cloned->itemsPerPage = null;

        return $cloned;
    }

    /**
     * @param int<0, MAX> $page
     * @param positive-int $itemsPerPage
     */
    public function withPagination(int $page, int $itemsPerPage): static
    {
        $cloned = clone $this;
        $cloned->page = $page;
        $cloned->itemsPerPage = $itemsPerPage;

        return $cloned;
    }

    /**
     * @param callable(QueryBuilder): mixed $filter
     *
     * @return static<T>
     */
    protected function filter(callable $filter): static
    {
        $cloned = clone $this;
        $filter($cloned->queryBuilder);

        return $cloned;
    }

    protected function query(): QueryBuilder
    {
        return clone $this->queryBuilder;
    }

    protected function __clone(): void
    {
        $this->queryBuilder = clone $this->queryBuilder;
    }
}
