<?php

declare(strict_types=1);

use SlevomatCodingStandard\Sniffs\Whitespaces\DuplicateSpacesSniff;
use Symplify\EasyCodingStandard\Config\ECSConfig;

$libRootPath = realpath(__DIR__ . '/../..');

return ECSConfig::configure()
    ->withSets([__DIR__ . '/vendor/brick/coding-standard/ecs.php'])
    ->withPaths(
        [
            $libRootPath . '/src',
            $libRootPath . '/tests',
            __FILE__,
        ],
    )
    ->withSkip([
        // Allows alignment in charset tables & test providers
        DuplicateSpacesSniff::class => [
            $libRootPath . '/src/Charset.php',
            $libRootPath . '/tests',
        ],
    ]);
