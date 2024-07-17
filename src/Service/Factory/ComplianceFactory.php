<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Service\Factory;

use Ghostwriter\Compliance\Compliance;
use Ghostwriter\Compliance\ServiceProvider;
use Ghostwriter\Container\Interface\ContainerInterface;
use Ghostwriter\Container\Interface\FactoryInterface;
use Override;
use Symfony\Component\Console\Application;
use Throwable;

/**
 * @implements FactoryInterface<Compliance>
 */
final readonly class ComplianceFactory implements FactoryInterface
{
    /**
     * @throws Throwable
     */
    #[Override]
    public function __invoke(ContainerInterface $container): Compliance
    {
        $container->provide(ServiceProvider::class);

        return new Compliance($container->get(Application::class), $container);
    }
}
