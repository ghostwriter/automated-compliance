<?php

declare(strict_types=1);

namespace Tests\Unit;

use Ghostwriter\Compliance\Container\ServiceProvider;
use Ghostwriter\Compliance\Value\Composer\Composer;
use Ghostwriter\Container\Container;
use Ghostwriter\Container\Interface\ContainerInterface;
use Override;
use PHPUnit\Framework\TestCase;
use Throwable;

abstract class AbstractTestCase extends TestCase
{
    public Composer $composer;

    public ContainerInterface $container;

    /**
     * @throws Throwable
     */
    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->container = Container::getInstance();

        $this->container->provide(ServiceProvider::class);

        $this->composer = $this->container->get(Composer::class);
    }

    /**
     * @throws Throwable
     */
    #[Override]
    protected function tearDown(): void
    {
        $this->container->purge();

        parent::tearDown();
    }
}
