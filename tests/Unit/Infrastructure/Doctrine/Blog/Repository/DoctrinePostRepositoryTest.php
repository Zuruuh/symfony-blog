<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Doctrine\Blog\Repository;

use Infrastructure\Doctrine\Blog\Repository\DoctrinePostRepository;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

#[CoversClass(DoctrinePostRepository::class)]
final class DoctrinePostRepositoryTest extends KernelTestCase
{
    public function testFind(): void
    {
        /* self::assertInstanceOf(DoctrinePostRepository::class, self::getContainer()->get(DoctrinePostRepository::class)); */
    }
}
