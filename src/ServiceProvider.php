<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance;

use Ghostwriter\Compliance\Interface\ToolInterface;
use Ghostwriter\Compliance\Service\Extension\ConfigExtension;
use Ghostwriter\Compliance\Service\Extension\ListenerProviderExtension;
use Ghostwriter\Compliance\Service\Extension\SymfonyApplicationExtension;
use Ghostwriter\Compliance\Service\Factory\SymfonyApplicationFactory;
use Ghostwriter\Compliance\Value\EnvironmentVariables;
use Ghostwriter\Config\Config;
use Ghostwriter\Config\ConfigFactory;
use Ghostwriter\Config\Contract\ConfigFactoryInterface;
use Ghostwriter\Config\Contract\ConfigInterface;
use Ghostwriter\Container\Attribute\Inject;
use Ghostwriter\Container\Interface\ContainerInterface;
use Ghostwriter\Container\Interface\ServiceProviderInterface;
use Ghostwriter\EventDispatcher\EventDispatcher;
use Ghostwriter\EventDispatcher\Interface\EventDispatcherInterface;
use Ghostwriter\EventDispatcher\Interface\ListenerProviderInterface;
use Ghostwriter\EventDispatcher\ListenerProvider;
use Ghostwriter\Filesystem\Filesystem;
use Ghostwriter\Filesystem\Interface\FilesystemInterface;
use Ghostwriter\Json\Interface\JsonInterface;
use Ghostwriter\Json\Json;
use Ghostwriter\Shell\Interface\RunnerInterface;
use Ghostwriter\Shell\Interface\ShellInterface;
use Ghostwriter\Shell\Runner;
use Ghostwriter\Shell\Shell;
use Override;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Formatter\OutputFormatter;
use Symfony\Component\Console\Formatter\OutputFormatterInterface;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\StyleInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use const DIRECTORY_SEPARATOR;

use function getcwd;
use function is_a;
use function sprintf;
use function str_contains;
use function str_ends_with;

final readonly class ServiceProvider implements ServiceProviderInterface
{
    public const array ALIASES = [
        Shell::class => ShellInterface::class,
        Runner::class => RunnerInterface::class,
        ArgvInput::class => InputInterface::class,
        Config::class => ConfigInterface::class,
        ConfigFactory::class => ConfigFactoryInterface::class,
        ConsoleOutput::class => OutputInterface::class,
        EventDispatcher::class => EventDispatcherInterface::class,
        Json::class => JsonInterface::class,
        ListenerProvider::class => ListenerProviderInterface::class,
        OutputFormatter::class => OutputFormatterInterface::class,
        SymfonyStyle::class => StyleInterface::class,
    ];

    public const array EXTENSIONS = [
        Application::class => SymfonyApplicationExtension::class,
        Config::class => ConfigExtension::class,
        ListenerProvider::class => ListenerProviderExtension::class,
    ];

    public const array FACTORIES = [
        Application::class => SymfonyApplicationFactory::class,
    ];

    public function __construct(
        #[Inject(Filesystem::class)]
        private FilesystemInterface $filesystem,
    ) {
    }

    #[Override]
    public function __invoke(ContainerInterface $container): void
    {
        $_ENV['GITHUB_EVENT_NAME'] ??= 'push';
        $_ENV['GITHUB_EVENT_PATH'] ??= 'tests/Fixture/payload.json';
        $_ENV['GITHUB_TOKEN'] ??= 'github-token';
        $_ENV['GITHUB_WORKSPACE'] ??= getcwd();
        $_ENV['RUNNER_DEBUG'] ??= 1;

        $container->set(EnvironmentVariables::class, EnvironmentVariables::new());
        $container->set(Shell::class, Shell::new());

        foreach (self::ALIASES as $service => $alias) {
            $container->alias($service, $alias);
        }

        foreach (self::EXTENSIONS as $service => $extension) {
            $container->extend($service, $extension);
        }

        foreach (self::FACTORIES as $service => $factory) {
            $container->factory($service, $factory);
        }

        // Tag the CLI tools we support.
        $namespace = __NAMESPACE__ . '\\Tool\\';
        foreach ($this->filesystem->findIn(__DIR__ . DIRECTORY_SEPARATOR . 'Tool') as $file) {
            $path = $file->getPathname();

            if (! str_ends_with($path, '.php')) {
                continue;
            }

            if (str_contains($path, 'Abstract')) {
                continue;
            }

            $service = sprintf('%s%s', $namespace, $file->getBasename('.php'));
            if (! is_a($service, ToolInterface::class, true)) {
                continue;
            }

            $container->tag($service, [ToolInterface::class]);
        }
    }
}
