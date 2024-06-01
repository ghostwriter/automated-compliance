<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Event;

use Ghostwriter\Compliance\Service\Job;
use Throwable;

final readonly class MatrixEvent extends AbstractEvent
{
    private Martix $matrix;

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
