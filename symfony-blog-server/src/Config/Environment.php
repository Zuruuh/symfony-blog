<?php

namespace App\Config;

use Symfony\Component\HttpKernel\KernelInterface;

class Environment
{
    public function __construct(
        private KernelInterface $kernel
    ) {
    }

    public function getEnv(): string
    {
        return $this->kernel->getEnvironment();
    }

    public function getParameter(string $parameter): ?string
    {
        return (string) $this->kernel->getContainer()->getParameter($parameter);
    }

    public function getRoot(): string
    {
        return $this->kernel->getProjectDir();
    }

    public function path(string $sprintf): string
    {
        return sprintf($sprintf, $this->getRoot());
    }

    public function isEnv(string $env): bool
    {
        return $this->getEnv() === $env;
    }
}
