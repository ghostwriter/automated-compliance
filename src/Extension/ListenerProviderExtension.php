<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Extension;

use Ghostwriter\Compliance\EnvironmentVariables;
use Ghostwriter\Compliance\Event\CheckEvent;
use Ghostwriter\Compliance\Event\GitHubEventInterface;
use Ghostwriter\Compliance\Event\MatrixEvent;
use Ghostwriter\Compliance\Event\OutputEvent;
use Ghostwriter\Compliance\Event\WorkflowEvent;
use Ghostwriter\Compliance\Listener\CheckListener;
use Ghostwriter\Compliance\Listener\Debug;
use Ghostwriter\Compliance\Listener\Logger;
use Ghostwriter\Compliance\Listener\GitHubListener;
use Ghostwriter\Compliance\Listener\MatrixListener;
use Ghostwriter\Compliance\Listener\OutputListener;
use Ghostwriter\Compliance\Service\Filesystem;
use Ghostwriter\Container\Interface\ContainerInterface;
use Ghostwriter\Container\Interface\ExtensionInterface;
use Ghostwriter\EventDispatcher\Interface\ExceptionInterface;
use Ghostwriter\EventDispatcher\ListenerProvider;

/**
 * @implements ExtensionInterface<ListenerProvider>
 */
final readonly class ListenerProviderExtension implements ExtensionInterface
{
    private const array EVENTS = [
        'object' => [
            //             Debug::class,
            //             Logger::class
        ],
        CheckEvent::class => [CheckListener::class],
        MatrixEvent::class => [MatrixListener::class],
        GitHubEventInterface::class => [GitHubListener::class],
        OutputEvent::class => [OutputListener::class],
        WorkflowEvent::class => [],
    ];

    public function __construct(
        private EnvironmentVariables $environmentVariables,
        private Filesystem $filesystem,
    ) {}

    /**
     * @param ListenerProvider $service
     *
     * @throws ExceptionInterface
     */
    public function __invoke(ContainerInterface $container, object $service): ListenerProvider
    {
        $events = self::EVENTS;

        if ($this->environmentVariables->get('GITHUB_DEBUG', '0') === '1') {
            $events['object'][] = Debug::class;
        }

        foreach ($events as $event => $listeners) {
            foreach ($listeners as $listener) {
                $service->bind($event, $listener);
            }
        }

        return $service;
    }
}
