<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Container\Extension;

use Ghostwriter\Compliance\Console\Command\CheckCommand;
use Ghostwriter\Compliance\Console\Command\MatrixCommand;
use Ghostwriter\Compliance\Console\Command\RunCommand;
use Ghostwriter\Compliance\Console\Command\WorkflowCommand;
use Ghostwriter\Container\Interface\ContainerInterface;
use Ghostwriter\Container\Interface\ExtensionInterface;
use Override;
use Symfony\Component\Console\Application as SymfonyApplication;

/**
 * @implements ExtensionInterface<SymfonyApplication>
 */
final readonly class SymfonyApplicationExtension implements ExtensionInterface
{
    private const array COMMANDS = [
        CheckCommand::class,
        MatrixCommand::class,
        RunCommand::class,
        WorkflowCommand::class,
    ];

    /**
     * @param SymfonyApplication $service
     */
    #[Override]
    public function __invoke(ContainerInterface $container, object $service): SymfonyApplication
    {
        $service->setAutoExit(false);
        $service->setCatchErrors(false);
        $service->setCatchExceptions(false);

        foreach (self::COMMANDS as $command) {
            $service->add($container->get($command));
        }

        $service->setDefaultCommand('run');

        return $service;
    }
}
