<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Enum;

enum OperatingSystem: string
{
    // case MACOS = 'macos';
    case UBUNTU = 'ubuntu';
    case WINDOWS = 'windows';

    // public function isMacos(): bool
    // {
    //     return $this === self::MACOS;
    // }

    public function isUbuntu(): bool
    {
        return self::UBUNTU === $this;
    }

    public function isWindows(): bool
    {
        return self::WINDOWS === $this;
    }

    public function toString(): string
    {
        return $this->value;
    }
}
