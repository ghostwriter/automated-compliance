<?php

declare(strict_types=1);

namespace Tests\Unit\Service;

use Ghostwriter\Compliance\Service\Composer;
use Tests\Unit\AbstractTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

use function getcwd;

use const DIRECTORY_SEPARATOR;

#[CoversClass(Composer::class)]
final class ComposerTest extends AbstractTestCase
{
    public function testGetJsonFilePath(): void
    {
        $root = getcwd();

        self::assertSame((new Composer())->getJsonFilePath($root), $root . DIRECTORY_SEPARATOR . 'composer.json');
    }

    public function testGetLockFilePath(): void
    {
        $root = getcwd();

        self::assertSame((new Composer())->getLockFilePath($root), $root . DIRECTORY_SEPARATOR . 'composer.lock');
    }
}
