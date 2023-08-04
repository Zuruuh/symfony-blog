<?php

declare(strict_types=1);

$finder = PhpCsFixer\Finder::create()
    ->in(dirname(__DIR__) . '/src')
    ->in(dirname(__DIR__) . '/tests')
    ->in(__DIR__)
    ->ignoreVCSIgnored(true)
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@PSR2' => true,
        '@PSR12' => true,
        '@PSR12:risky' => true,
        '@PHP80Migration' => true,
        '@PHP80Migration:risky' => true,
        'single_line_empty_body' => true,
        'ordered_imports' => true,
    ])
    ->setFinder($finder)
    ->setUsingCache(true)
    ->setCacheFile(dirname(__dir__) . '/.cache/.php-cs-fixer.cache')
;
