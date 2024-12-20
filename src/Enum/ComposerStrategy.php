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
        return self::LATEST === $this;
    }

    public function isLocked(): bool
    {
        // Installing locked dependencies as specified in lockfile via Composer
        return self::LOCKED === $this;
    }

    public function isLowest(): bool
    {
        // Installing lowest supported dependencies via Composer
        return self::LOWEST === $this;
    }

    public function toString(): string
    {
        return $this->value;
    }
}
