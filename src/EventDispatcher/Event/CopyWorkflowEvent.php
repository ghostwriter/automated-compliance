<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\EventDispatcher\Event;

final readonly class CopyWorkflowEvent
{
    private const string WORKFLOW_FILE = __DIR__ . '/../../automation.yml.dist';

    public function __construct(
        private string $to,
        private bool $overwrite,
    ) {
    }

    public function from(): string
    {
        return self::WORKFLOW_FILE;
    }

    public function overwrite(): bool
    {
        return $this->overwrite;
    }

    public function to(): string
    {
        return $this->to;
    }

    public static function new(string $to, bool $overwrite = false): self
    {
        return new self($to, $overwrite);
    }
}
