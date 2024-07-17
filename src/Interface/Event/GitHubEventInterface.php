<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Interface\Event;

interface GitHubEventInterface
{
    public function payload(): string;
}
