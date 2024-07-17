<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Value\Composer;

use Generator;
use Ghostwriter\Json\Json;
use InvalidArgumentException;
use IteratorAggregate;
use JsonSerializable;
use Override;
use Stringable;

use function array_map;

/**
 * @implements IteratorAggregate<Package>
 */
final readonly class Packages implements IteratorAggregate, JsonSerializable, Stringable
{
    /**
     * @param array<Package> $packages
     */
    public function __construct(
        private array $packages
    ) {
        foreach ($packages as $package) {
            if (! $package instanceof Package) {
                throw new InvalidArgumentException('Packages must be an array of Package objects');
            }
        }
    }

    #[Override]
    public function __toString(): string
    {
        return (new Json())->encode($this);
    }

    /**
     * @return Generator<Package>
     */
    #[Override]
    public function getIterator(): Generator
    {
        yield from $this->packages;
    }

    #[Override]
    public function jsonSerialize(): array
    {
        return array_map(static fn (Package $package): string => (new Json())->encode($package), $this->packages);
    }
}
