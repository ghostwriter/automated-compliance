<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Enum;

enum ComposerStrategy: string
{
    case LATEST = 'latest'; // composer update
    case LOCKED = 'locked'; // composer install
    case LOWEST = 'lowest'; // composer update --prefer-lowest --prefer-stable

    public function toString(): string
    {
        return $this->value;
    }

    public static function isExperimental(self $composerStrategy): bool
    {
        return $composerStrategy === self::LOWEST;
    }
}
