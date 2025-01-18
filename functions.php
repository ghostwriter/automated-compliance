<?php

declare(strict_types=1);

use Ghostwriter\Compliance\Compliance;
use Ghostwriter\Compliance\EventDispatcher\Event\OutputEvent;
use Ghostwriter\Compliance\Value\GitHub\Action\Output\GitHubActionOutput;
use Ghostwriter\Container\Container;
use Ghostwriter\Container\Interface\ContainerInterface;
use Ghostwriter\EventDispatcher\Interface\EventDispatcherInterface;
use Ghostwriter\Filesystem\Interface\FilesystemInterface;
use Ghostwriter\Shell\Interface\ResultInterface;
use Ghostwriter\Shell\Interface\ShellInterface;

if (! \function_exists('container')) {
    /**
     * @throws \Throwable
     */
    function container(): ContainerInterface
    {
        return Container::getInstance();
    }
}

if (! \function_exists('filesystem')) {
    /**
     * @throws \Throwable
     */
    function filesystem(): FilesystemInterface
    {
        return \container()
            ->get(FilesystemInterface::class);
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
     * @param list<string>          $arguments
     * @param array<string, string> $environmentVariables
     *
     * @throws \Throwable
     *
     * @return ResultInterface
     */
    function execute(
        string $command,
        array $arguments = [],
        ?string $workingDirectory = null,
        ?array $environmentVariables = null,
        ?string $input = null,
    ): ResultInterface {
        return \container()
            ->get(ShellInterface::class)
            ->execute($command, $arguments, $workingDirectory, $environmentVariables, $input);
    }
}
