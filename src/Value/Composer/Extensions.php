<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Value\Composer;

use Generator;
use InvalidArgumentException;
use IteratorAggregate;
use JsonSerializable;
use Override;
use Stringable;

/**
 * @implements IteratorAggregate<Extension>
 */
final readonly class Extensions implements IteratorAggregate, JsonSerializable, Stringable
{
    /**
     * @param array<Extension> $extensions
     */
    public function __construct(
        private array $extensions
    ) {
        if ($extensions === []) {
            throw new InvalidArgumentException('Extensions cannot be empty');
        }

        foreach ($extensions as $extension) {
            if (! $extension instanceof Extension) {
                throw new InvalidArgumentException('Extensions must be an array of Extension objects');
            }
        }
    }

    #[Override]
    public function __toString(): string
    {
        return \implode(', ', $this->jsonSerialize());
    }

    /**
     * @return Generator<Extension>
     */
    #[Override]
    public function getIterator(): Generator
    {
        yield from $this->extensions;
    }

    #[Override]
    public function jsonSerialize(): array
    {
        return \array_map(static fn (Extension $extension): string => (string) $extension, $this->extensions);
    }
}
