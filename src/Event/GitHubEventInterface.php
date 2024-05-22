<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Event;

interface GitHubEventInterface
{
    public function payload(): string;
}
