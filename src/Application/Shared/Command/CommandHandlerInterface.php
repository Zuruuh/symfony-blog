<?php

declare(strict_types=1);

namespace Application\Shared\Command;

/**
 * @template TCommand of CommandInterface
 * @template TValue
 *
 * @method TValue __invoke(TCommand)
 */
interface CommandHandlerInterface {}
