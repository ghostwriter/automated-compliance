<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Event\GitHub;

use Ghostwriter\Compliance\Interface\Event\GitHubEventInterface;
use Override;

/**
 * @template TStopped of bool
 *
 * @implements GitHubEventInterface<TStopped>
 */
final readonly class GitHubMilestoneEvent implements GitHubEventInterface
{
    public function __construct(
        private string $content
    ) {
    }

    #[Override]
    public function payload(): string
    {
        return $this->content;
    }
}
