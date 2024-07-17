<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Listener;

use Ghostwriter\Compliance\Event\OutputEvent;
use Ghostwriter\Compliance\Interface\Event\Listener\ListenerInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final readonly class OutputListener implements ListenerInterface
{
    public function __construct(
        private SymfonyStyle $symfonyStyle,
    ) {
    }

    public function __invoke(OutputEvent $outputEvent): void
    {
        $this->symfonyStyle->writeln($outputEvent->getMessage());
    }
}
