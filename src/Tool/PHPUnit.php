<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Tool;

final class PHPUnit extends AbstractTool
{
    public function command(): string
    {
        return $this->phive('phpunit') . ' --do-not-cache-result --colors=always --stop-on-failure';
    }

    /**
     * @return string[]
     */
    public function configuration(): array
    {
        return ['phpunit.xml.dist', 'phpunit.xml'];
    }
}
