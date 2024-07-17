<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Event;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Style\StyleInterface;

abstract readonly class AbstractEvent
{
    public function __construct(
        protected InputInterface $input,
        protected StyleInterface $style
    ) {
    }

    public function input(): InputInterface
    {
        return $this->input;
    }

    public function output(): StyleInterface
    {
        return $this->style;
    }
}
