<?php
/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

use PhpCsFixer\Fixer\ArrayNotation\ArraySyntaxFixer;
use PhpCsFixer\Fixer\CastNotation\CastSpacesFixer;
use PhpCsFixer\Fixer\Import\NoUnusedImportsFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;

return ECSConfig::configure()
    ->withPaths([
        __DIR__ . '/app',
        __DIR__ . '/src',
        __DIR__ . '/tests'
    ])
    ->withRules([
        ArraySyntaxFixer::class,
        NoUnusedImportsFixer::class,
        CastSpacesFixer::class,
    ])
    ->withPreparedSets(psr12: true);
