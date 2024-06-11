<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Service;

interface PhpVersionConstraintInterface
{
    public const array SUPPORTED = ['7.4', '8.0', '8.1'];

    public const string LATEST = 'latest';

    public function getVersion(): string;
}
