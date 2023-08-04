<?php

declare(strict_types=1);

use Infrastructure\Symfony\Kernel;

require_once dirname(__DIR__, 4) . '/vendor/autoload_runtime.php';

/**
 * @param array{APP_ENV: string, APP_DEBUG: string} $context
 */
return static fn (array $context): Kernel => new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
