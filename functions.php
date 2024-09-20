<?php

declare(strict_types=1);

use Ghostwriter\Compliance\Compliance;
use Ghostwriter\Compliance\EventDispatcher\Event\OutputEvent;
use Ghostwriter\Compliance\Value\GitHub\Action\Output\GitHubActionOutput;
use Ghostwriter\Container\Container;
use Ghostwriter\Container\Interface\ContainerInterface;
use Ghostwriter\EventDispatcher\Interface\EventDispatcherInterface;
use Ghostwriter\Shell\Interface\ResultInterface;
use Ghostwriter\Shell\Shell;

if (! \function_exists('container')) {
    /**
     * @throws \Throwable
     */
    function container(): ContainerInterface
    {
        return Container::getInstance();
    }
}

if (! \function_exists('dispatch')) {
    /**
     * @template T of object
     *
     * @param T $event
     *
     * @throws \Throwable
     *
     * @return T
     */
    function dispatch(object $event): object
    {
        return \container()
            ->get(EventDispatcherInterface::class)
            ->dispatch($event);
    }
}

if (! \function_exists('dispatchOutputEvent')) {
    /**
     * @throws \Throwable
     */
    function dispatchOutputEvent(string $message): OutputEvent
    {
        return \dispatch(OutputEvent::new(
            [
                '::echo::on',
                \sprintf('::group::%s %s', Compliance::NAME, Compliance::BLACK_LIVES_MATTER),
                $message,
                '::endgroup::',
                '::echo::off',
            ]
        ));
    }

}

if (! \function_exists('githubActionOutput')) {
    /**
     * @throws \Throwable
     */
    function githubActionOutput(): GitHubActionOutput
    {
        return \container()
            ->get(GitHubActionOutput::class);
    }

}

if (! \function_exists('debug')) {
    /**
     * @throws \Throwable
     */
    function debug(string $message, ?string $file = null, ?int $line = null, ?int $col = null): void
    {
        \githubActionOutput()
            ->debug($message, $file, $line, $col);
    }

}

if (! \function_exists('warning')) {
    /**
     * @throws \Throwable
     */
    function warning(string $message, ?string $file = null, ?int $line = null, ?int $col = null): void
    {
        \githubActionOutput()
            ->warning($message, $file, $line, $col);
    }

}

if (! \function_exists('error')) {
    /**
     * @throws \Throwable
     */
    function error(string $message, ?string $file = null, ?int $line = null, ?int $col = null): void
    {
        \githubActionOutput()
            ->error($message, $file, $line, $col);
    }

}

if (! \function_exists('execute')) {
    /**
     * @throws \Throwable
     */
    function execute(string $command, string ...$arguments): ResultInterface
    {
        return Shell::new()->execute($command, $arguments);
    }
}
