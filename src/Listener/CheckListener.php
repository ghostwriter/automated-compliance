<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Listener;

use Ghostwriter\Compliance\Event\CheckEvent;
use Ghostwriter\Compliance\Event\OutputEvent;
use Ghostwriter\Compliance\Interface\EventListenerInterface;
use Throwable;

final readonly class CheckListener implements EventListenerInterface
{
    /**
     * @throws Throwable
     */
    public function __invoke(CheckEvent $checkEvent): void
    {
        /** @var string $job */
        $job = $checkEvent->input()
            ->getArgument('job');

        $checkEvent->dispatch(OutputEvent::new($job));
    }
}
