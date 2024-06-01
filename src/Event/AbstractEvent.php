<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Event;

use Ghostwriter\EventDispatcher\Interface\EventDispatcherInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

abstract readonly class AbstractEvent
{
    public function __construct(
        protected EventDispatcherInterface $dispatcher,
        protected InputInterface $input,
        protected SymfonyStyle $symfonyStyle
    ) {}

    public function dispatch(object $event): object
    {
        return $this->dispatcher->dispatch($event);
    }

    public function dispatcher(): EventDispatcherInterface
    {
        return $this->dispatcher;
    }

    public function input(): InputInterface
    {
        return $this->input;
    }

    public function output(): SymfonyStyle
    {
        return $this->symfonyStyle;
    }
}
