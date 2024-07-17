<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Command;

use Ghostwriter\Container\Interface\ContainerInterface;
use Ghostwriter\EventDispatcher\Interface\EventDispatcherInterface;
use Override;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;

use const PHP_EOL;

use function mb_strtolower;
use function sprintf;
use function str_replace;

abstract class AbstractCommand extends Command
{
    public function __construct(
        protected ContainerInterface $container,
        protected EventDispatcherInterface $eventDispatcher,
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
            $this->eventDispatcher->dispatch($this->container->build($event));
        } catch (Throwable $throwable) {
            $this->symfonyStyle->error(
                sprintf(
                    '[%s] %s%s%s' . PHP_EOL,
                    $throwable::class,
                    $throwable->getMessage(),
                    PHP_EOL . PHP_EOL,
                    $throwable->getTraceAsString(),
                )
            );

            return self::FAILURE;
        }

        return self::SUCCESS;
    }

    //    public function write(string $message): int
    //    {
    //        try {
    //            $this->eventDispatcher->dispatch(
    //                OutputEvent::new([
    //                    '::echo::on',
    //                    sprintf('::group::%s %s', Compliance::NAME, Compliance::BLACK_LIVES_MATTER),
    //                    $message,
    //                    '::endgroup::',
    //                    '::echo::off',
    //                ])
    //            );
    //        } catch (Throwable $throwable) {
    //            $this->symfonyStyle->error(
    //                sprintf(
    //                    '[%s] %s%s%s' . PHP_EOL,
    //                    $throwable::class,
    //                    $throwable->getMessage(),
    //                    PHP_EOL . PHP_EOL,
    //                    $throwable->getTraceAsString(),
    //                )
    //            );
    //
    //            return self::FAILURE;
    //        }
    //
    //        return self::SUCCESS;
    //    }

    #[Override]
    public static function getDefaultName(): string
    {
        return mb_strtolower(str_replace([__NAMESPACE__ . '\\', 'Command'], '', static::class));
    }
}
