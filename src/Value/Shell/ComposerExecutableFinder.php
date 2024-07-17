<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Value\Shell;

final readonly class ComposerExecutableFinder
{
    public function __construct(
        private WhereExecutableFinder $whereExecutableFinder,
    ) {
    }

    public function __invoke(): string
    {
        return ($this->whereExecutableFinder)('composer');
    }
}
