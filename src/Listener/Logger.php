<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Listener;

use Ghostwriter\Compliance\Event\GitHub\GitHubPushEvent;
use Ghostwriter\Compliance\Event\GitHub\GitHubScheduleEvent;
use Ghostwriter\Compliance\Event\GitHub\GitHubWorkflowCallEvent;
use Ghostwriter\Compliance\Event\GitHub\GitHubWorkflowDispatchEvent;
use Ghostwriter\Compliance\Event\GitHub\GitHubWorkflowRunEvent;
use Ghostwriter\Compliance\Event\MatrixEvent;
use Ghostwriter\Compliance\Interface\Event\GitHubEventInterface;
use Ghostwriter\Compliance\Interface\Event\Listener\ListenerInterface;
use Ghostwriter\EventDispatcher\Interface\EventDispatcherInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final readonly class Logger implements ListenerInterface
{
    public function __construct(
        private EventDispatcherInterface $eventDispatcher,
        private SymfonyStyle $symfonyStyle,
    ) {
    }

    public function __invoke(GitHubEventInterface $gitHubEvent): void
    {
        $this->symfonyStyle->info('Event Class: ' . $gitHubEvent::class);
        $this->symfonyStyle->info('Event Payload: ' . $gitHubEvent->payload());

        $stop = match (true) {
            //            $event instanceof GitHubPullRequestEvent,
            $gitHubEvent instanceof GitHubWorkflowCallEvent,
            $gitHubEvent instanceof GitHubWorkflowDispatchEvent,
            $gitHubEvent instanceof GitHubScheduleEvent,
            $gitHubEvent instanceof GitHubWorkflowRunEvent,
            $gitHubEvent instanceof GitHubPushEvent => false,
            default => true,
        };

        if ($stop) {
            return;
        }

        $this->eventDispatcher->dispatch(MatrixEvent::new());

        //        $this->symfonyStyle->info(sprintf(
        //            '<fg=white;bg=black;options=bold>Event Class:</> <info>%s</info>',
        //            $event::class
        //        ));
        //
        //        $this->symfonyStyle->info(sprintf(
        //            '<fg=white;bg=black;options=bold>Event Payload:</> <info>%s</info>',
        //            $event->payload()
        //        ));
    }
}
