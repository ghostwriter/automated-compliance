<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Service;

interface ComposerFileType
{
    /**
     * @var string
     */
    public const string JSON = 'json';

    /**
     * @var string
     */
    public const string LOCK = 'lock';
}
