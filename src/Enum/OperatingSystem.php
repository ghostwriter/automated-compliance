<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Enum;

enum OperatingSystem: string
{
    case MACOS = 'macos';
    case UBUNTU = 'ubuntu';
    case WINDOWS = 'windows';

    public function isMacos(): bool
    {
        return $this === self::MACOS;
    }

    public function isUbuntu(): bool
    {
        return $this === self::UBUNTU;
    }

    public function isWindows(): bool
    {
        return $this === self::WINDOWS;
    }

    public function toString(): string
    {
        return $this->value;
    }
}
