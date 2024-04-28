<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Tool;

final class Psalm extends AbstractTool
{
    public function command(): string
    {
        return 'composer ghostwriter:psalm';
    }

    /**
     * @return string[]
     */
    public function configuration(): array
    {
        return ['psalm.xml.dist', 'psalm.xml'];
    }
}
