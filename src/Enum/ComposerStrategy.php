<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Enum;

enum ComposerStrategy: string
{
    case LATEST = 'latest'; // composer update
    case LOCKED = 'locked'; // composer install
    case LOWEST = 'lowest'; // composer update --prefer-lowest --prefer-stable

    public function isLatest(): bool
    {
        // Installing latest supported dependencies via Composer
        return $this === self::LATEST;
    }

    public function isLocked(): bool
    {
        // Installing locked dependencies as specified in lockfile via Composer
        return $this === self::LOCKED;
    }

    public function isLowest(): bool
    {
        // Installing lowest supported dependencies via Composer
        return $this === self::LOWEST;
    }

    public function toString(): string
    {
        return $this->value;
    }
}
