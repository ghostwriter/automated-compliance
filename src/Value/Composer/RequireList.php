<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Value\Composer;

use Generator;
use Ghostwriter\Json\Interface\JsonInterface;
use IteratorAggregate;
use Override;

/**
 * @implements IteratorAggregate<Package|Extension>
 */
final readonly class RequireList implements IteratorAggregate
{
    /**
     * @param array<Extension|Package> $requireList
     */
    public function __construct(
        private array $requireList,
    ) {
    }

    #[Override]
    public function getIterator(): Generator
    {
        yield from $this->requireList;
    }

    public static function new(array $require, JsonInterface $json): self
    {
        $requireList = [];

        foreach ($require as $name => $version) {
            $dependencyName = DependencyName::new($name);
            $dependencyVersion = DependencyVersion::new($version);

            $requireList[$name] = $dependencyName->isPhpExtension()
                ? Extension::new($dependencyName, $dependencyVersion)
                : Package::new($dependencyName, $dependencyVersion, $json);
        }

        return new self($requireList);
    }
}
