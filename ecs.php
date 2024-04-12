<?php
/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

use PhpCsFixer\Fixer\ArrayNotation\ArraySyntaxFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;

return ECSConfig::configure()
    ->withPaths([
        __DIR__ . '/app',
        __DIR__ . '/src',
        __DIR__ . '/tests'
    ])
    ->withRules([
        ArraySyntaxFixer::class,
    ])
    ->withPreparedSets(psr12: true);
