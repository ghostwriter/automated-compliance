<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Listener;

use Ghostwriter\Compliance\Interface\Event\Listener\ListenerInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use function mb_strrpos;
use function mb_substr;
use function sprintf;

final readonly class Debug implements ListenerInterface
{
    public function __construct(
        private SymfonyStyle $symfonyStyle
    ) {
    }

    public function __invoke(object $event): void
    {
        $eventName = mb_substr($event::class, mb_strrpos($event::class, '\\') + 1);

        $this->symfonyStyle->title(sprintf(
            '<fg=white;bg=black;options=bold>DEBUG START:</> <info>%s</info>',
            $eventName
        ));

        $this->symfonyStyle->table(['name', 'class'], [[$eventName, $event::class]]);

        $this->symfonyStyle->title(sprintf(
            '<fg=white;bg=black;options=bold>DEBUG END:  </> <info>%s</info>',
            $eventName
        ));
    }
}
