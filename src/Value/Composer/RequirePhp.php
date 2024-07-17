<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Value\Composer;

use Override;

final readonly class RequirePhp implements PhpVersionConstraintInterface
{
    public function __construct(
        private string $version,
    ) {
    }

    #[Override]
    public function getVersion(): string
    {
        return $this->version;
    }

    public static function new(string $phpVersion): self
    {
        return new self($phpVersion);
    }
}
