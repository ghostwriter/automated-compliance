<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Event\GitHub;

use Ghostwriter\Compliance\Event\GitHubEventInterface;

/**
 * @implements GitHubEventInterface<bool>
 */
final class GitHubCreateEvent implements GitHubEventInterface
{
    public function __construct(
        private string $content
    ) {}

    public function payload(): string
    {
        return $this->content;
    }
}
