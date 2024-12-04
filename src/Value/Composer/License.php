<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Value\Composer;

use InvalidArgumentException;
use JsonSerializable;
use Override;
use Stringable;

final readonly class License implements JsonSerializable, Stringable
{
    public function __construct(
        private string $content
    ) {
    }

    #[Override]
    public function __toString(): string
    {
        return $this->content;
    }

    #[Override]
    public function jsonSerialize(): array
    {
        return [$this->content];
    }

    public static function new(?string $content): self
    {
        return match (true) {
            $content === null => throw new InvalidArgumentException('License cannot be null'),
            \mb_trim($content) === '' => throw new InvalidArgumentException('License cannot be empty'),
            default => new self($content)
        };
    }
}
