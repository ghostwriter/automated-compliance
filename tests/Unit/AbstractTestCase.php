<?php

declare(strict_types=1);

namespace Tests\Unit;

use Override;
use PHPUnit\Framework\TestCase;

abstract class AbstractTestCase extends TestCase
{
    #[Override]
    protected function setUp(): void
    {
        parent::setUp();
    }
}
