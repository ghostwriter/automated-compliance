<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Value\Composer;

use Composer\InstalledVersions;
use RuntimeException;
use Throwable;

final readonly class InstalledVersionsResolver
{
    /**
     * @throws Throwable
     */
    public function resolve(string $package): string
    {
        return InstalledVersions::getPrettyVersion($package) ?? throw new RuntimeException(\sprintf(
            'Unable to resolve installed version for "%s" package.',
            $package
        ));
    }
}
