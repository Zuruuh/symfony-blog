<?php

declare(strict_types=1);

namespace Infrastructure\Symfony;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

final class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    public function getProjectDir(): string
    {
        return dirname(__DIR__);
    }

    /**
     * @override
     */
    public function getCacheDir(): string
    {
        return dirname(__DIR__, 4) . "/.cache/symfony/{$this->environment}";
    }
}
