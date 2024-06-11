<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Event;

use Ghostwriter\Container\Container;
use Ghostwriter\EventDispatcher\Interface\EventDispatcherInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;

final readonly class OutputEvent extends AbstractEvent
{
    public function __construct(
        private array|string $message,
        protected EventDispatcherInterface $dispatcher,
        protected InputInterface $input,
        protected SymfonyStyle $symfonyStyle
    ) {
        parent::__construct($dispatcher, $input, $symfonyStyle);
    }

    public function getMessage(): array|string
    {
        return $this->message;
    }

    /**
     * @throws Throwable
     */
    public static function new(array|string $message): self
    {
        $container = Container::getInstance();

        return $container->build(self::class, [$message]);
    }
}
