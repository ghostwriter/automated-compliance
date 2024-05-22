<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Command;

use Ghostwriter\Compliance\Compliance;
use Ghostwriter\Compliance\Event\OutputEvent;
use Ghostwriter\Container\Interface\ContainerInterface;
use Ghostwriter\EventDispatcher\Interface\EventDispatcherInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;

use function mb_strtolower;
use function sprintf;
use function str_replace;

abstract class AbstractCommand extends Command
{
    public function __construct(
        protected ContainerInterface $container,
        protected EventDispatcherInterface $dispatcher,
        protected SymfonyStyle $symfonyStyle
    ) {
        parent::__construct(static::getDefaultName());
    }

    /**
     * @param class-string $event
     *
     * @return int 0 if everything went fine, or an exit code
     */
    public function dispatch(string $event): int
    {
        try {
            $this->dispatcher->dispatch($this->container->build($event));
        } catch (Throwable $e) {
            $this->symfonyStyle->error($e->getMessage());

            return self::FAILURE;
        }

        return self::SUCCESS;
    }

    public function write(string $message): int
    {
        try {
            $this->dispatcher->dispatch(
                new OutputEvent([
                    '::echo::on',
                    sprintf('::group::%s %s', Compliance::NAME, Compliance::BLACK_LIVES_MATTER),
                    $message,
                    '::endgroup::',
                    '::echo::off',
                ])
            );
        } catch (Throwable $e) {
            $this->symfonyStyle->error($e->getMessage());

            return self::FAILURE;
        }

        return self::SUCCESS;
    }

    public static function getDefaultName(): string
    {
        return mb_strtolower(str_replace([__NAMESPACE__ . '\\', 'Command'], '', static::class));
    }
}
