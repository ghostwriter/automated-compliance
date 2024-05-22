<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Event\GitHub;

use Ghostwriter\Compliance\Event\GitHubEventInterface;

/**
 * @template TStopped of bool
 *
 * @implements GitHubEventInterface<TStopped>
 */
final class GitHubMilestoneEvent implements GitHubEventInterface
{
    public function __construct(
        private string $content
    ) {}

    public function payload(): string
    {
        return $this->content;
    }
}
