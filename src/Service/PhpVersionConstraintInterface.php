<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Service;

interface PhpVersionConstraintInterface
{
    public const string LATEST = 'latest';

    public const array SUPPORTED = ['7.4', '8.0', '8.1'];

    public function getVersion(): string;
}
