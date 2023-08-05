<?php

declare(strict_types=1);

namespace Application\Shared\Query;

/**
 * @template TQuery of QueryInterface
 * @template TValue
 *
 * @method TValue __invoke(TQuery)
 */
interface QueryHandlerInterface {}
