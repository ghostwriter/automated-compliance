<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Event;

use Ghostwriter\Compliance\Value\GitHub\Action\Job;
use Ghostwriter\Compliance\Value\GitHub\Action\Matrix;
use Ghostwriter\Container\Container;
use Throwable;

final readonly class MatrixEvent
{
    public function __construct(
        private Matrix $matrix,
    ) {
    }

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

    public static function new(): self
    {
        return Container::getInstance()->get(self::class);
    }
}
