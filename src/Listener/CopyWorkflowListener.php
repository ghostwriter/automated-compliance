<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Listener;

use Ghostwriter\Compliance\Event\CopyWorkflowEvent;
use Ghostwriter\Compliance\Interface\Event\Listener\ListenerInterface;
use Ghostwriter\Filesystem\Interface\FilesystemInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final readonly class CopyWorkflowListener implements ListenerInterface
{
    public function __construct(
        private FilesystemInterface $fileSystem,
        private SymfonyStyle $symfonyStyle,
    ) {
    }

    public function __invoke(CopyWorkflowEvent $copyWorkflowEvent): void
    {
        $to = $copyWorkflowEvent->to();

        if ($this->fileSystem->exists($to) && ! $copyWorkflowEvent->overwrite()) {
            $this->symfonyStyle->error('The file already exists. Use the --overwrite option to overwrite it.');
            return;
        }

        $this->fileSystem->copy($copyWorkflowEvent->from(), $to);

        $this->symfonyStyle->success('The "automation.yml" workflow file has been created.');

    }
}
