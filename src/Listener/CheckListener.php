<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Listener;

use Ghostwriter\Compliance\Event\CheckEvent;
use Ghostwriter\Compliance\Event\OutputEvent;
use Ghostwriter\Compliance\Interface\Event\Listener\ListenerInterface;
use Ghostwriter\EventDispatcher\Interface\EventDispatcherInterface;
use Throwable;

final readonly class CheckListener implements ListenerInterface
{
    public function __construct(
        private EventDispatcherInterface $eventDispatcher
    ) {
    }

    /**
     * @throws Throwable
     */
    public function __invoke(CheckEvent $checkEvent): void
    {
        $this->eventDispatcher->dispatch(OutputEvent::new($checkEvent->input()->getArgument('job')));
    }
}
