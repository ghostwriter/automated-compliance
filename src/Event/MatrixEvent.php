<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Event;

use Ghostwriter\Compliance\Service\Job;
use Ghostwriter\EventDispatcher\Interface\EventDispatcherInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;

final readonly class MatrixEvent extends AbstractEvent
{
    public function __construct(
        private Martix $matrix,
        protected EventDispatcherInterface $dispatcher,
        protected InputInterface $input,
        protected SymfonyStyle $symfonyStyle
    ) {}

    public function exclude(array $matrices): void
    {
        $this->matrix->exclude($matrices);
    }

    /**
     * @throws Throwable
     */
    public function getMatrix(): string
    {
        return $this->matrix->toString();
    }

    public function include(Job $job): void
    {
        $this->matrix->include($job);
    }
}
