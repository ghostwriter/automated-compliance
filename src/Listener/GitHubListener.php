<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Listener;

use Ghostwriter\EventDispatcher\Interface\EventDispatcherInterface;
use Ghostwriter\Container\Interface\ContainerInterface;
use Ghostwriter\Compliance\Event\MatrixEvent;
use Ghostwriter\Compliance\Event\GitHubEventInterface;
use Ghostwriter\Compliance\Interface\EventListenerInterface;

final readonly class GitHubListener implements EventListenerInterface
{
    public function __construct(
        private ContainerInterface $container,
        private EventDispatcherInterface $dispatcher,
    ) {}

    public function __invoke(GitHubEventInterface $outputEvent): void
    {
        $this->dispatcher->dispatch($this->container->build(MatrixEvent::class));
    }
}
