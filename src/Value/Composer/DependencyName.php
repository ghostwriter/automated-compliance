<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Value\Composer;

use InvalidArgumentException;
use JsonSerializable;
use Override;
use Stringable;

final readonly class DependencyName implements JsonSerializable, Stringable
{
    public function __construct(
        private string $content
    ) {
        if (\mb_trim($content) === '') {
            throw new InvalidArgumentException('Name cannot be empty');
        }
    }

    #[Override]
    public function __toString(): string
    {
        return $this->content;
    }

    public function isPhpExtension(): bool
    {
        return \str_starts_with($this->content, 'ext-');
    }

    #[Override]
    public function jsonSerialize(): array
    {
        return [$this->content];
    }

    public static function new(string $name): self
    {
        return new self($name);
    }
}
