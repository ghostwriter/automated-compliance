<?php

declare(strict_types=1);

use Composer\Autoload\ClassLoader;

/** @var ClassLoader $classLoader */
$classLoader = require \dirname(__DIR__) . \DIRECTORY_SEPARATOR . 'vendor' . \DIRECTORY_SEPARATOR . 'autoload.php';

if (! $classLoader instanceof ClassLoader) {
    throw new \RuntimeException('Class loader not found');
}

$fixturePath = __DIR__ . \DIRECTORY_SEPARATOR . 'fixture';

// when test fixtures have a single namespace (e.g. Tests\Fixture)
$classLoader->addPsr4('Tests\\Fixture\\', $fixturePath);

// when test fixtures have multiple namespaces
$classLoader->addPsr4('', $fixturePath);

return $classLoader;
