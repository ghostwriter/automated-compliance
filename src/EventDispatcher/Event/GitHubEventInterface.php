<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\EventDispatcher\Event;

interface GitHubEventInterface
{
    public function payload(): string;
}
