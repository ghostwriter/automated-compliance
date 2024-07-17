<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Service\Factory;

use Composer\InstalledVersions;
use Ghostwriter\Compliance\Compliance;
use Ghostwriter\Container\Interface\ContainerInterface;
use Ghostwriter\Container\Interface\FactoryInterface;
use Override;
use RuntimeException;
use Symfony\Component\Console\Application;
use Throwable;

/**
 * @implements FactoryInterface<Application>
 */
final readonly class SymfonyApplicationFactory implements FactoryInterface
{
    /**
     * @throws Throwable
     */
    #[Override]
    public function __invoke(ContainerInterface $container): Application
    {
        return new Application(
            Compliance::NAME,
            InstalledVersions::getPrettyVersion(Compliance::PACKAGE) ??
            throw new RuntimeException('Unable to determine version!')
        );
    }
}
