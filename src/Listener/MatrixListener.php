<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Listener;

use Composer\Semver\Semver;
use Ghostwriter\Compliance\Automation;
use Ghostwriter\Compliance\Enum\ComposerStrategy;
use Ghostwriter\Compliance\Enum\OperatingSystem;
use Ghostwriter\Compliance\Enum\PhpVersion;
use Ghostwriter\Compliance\Enum\Tool;
use Ghostwriter\Compliance\Event\MatrixEvent;
use Ghostwriter\Compliance\Interface\Event\Listener\ListenerInterface;
use Ghostwriter\Compliance\Interface\ToolInterface;
use Ghostwriter\Compliance\Tool\PHPUnit;
use Ghostwriter\Compliance\Tool\Psalm;
use Ghostwriter\Compliance\Value\Composer\Composer;
use Ghostwriter\Compliance\Value\Composer\Extension;
use Ghostwriter\Compliance\Value\EnvironmentVariables;
use Ghostwriter\Compliance\Value\GitHub\Action\Job;
use Ghostwriter\Compliance\Value\Shell\ComposerCacheFilesDirectoryFinder;
use Ghostwriter\Container\Interface\ContainerInterface;
use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Throwable;

use const FILE_APPEND;
use const PHP_EOL;

use function array_map;
use function array_unique;
use function chdir;
use function dispatchOutputEvent;
use function file_put_contents;
use function getcwd;
use function iterator_to_array;
use function sprintf;
use function sys_get_temp_dir;
use function tempnam;

final readonly class MatrixListener implements ListenerInterface
{
    public function __construct(
        private Automation $automation,
        // TODO: remove the damn container im using it to get the tools that were tagged
        private ContainerInterface $container,
        private Composer $composer,
        private ComposerCacheFilesDirectoryFinder $composerCacheFilesDirectoryFinder,
        private EnvironmentVariables $environmentVariables,
    ) {
    }

    public function __invoke(MatrixEvent $generateMatrixEvent): void
    {
        $root = getcwd();

        if ($root === false) {
            throw new RuntimeException('Could not get current working directory');
        }

        chdir($root);

        $composerCacheFilesDirectory = ($this->composerCacheFilesDirectoryFinder)();

        $composerJsonPath = $this->composer->getJsonFilePath($root);
        $composerLockPath = $this->composer->getLockFilePath($root);
        $composerJson = $this->composer->readJsonFile($root);

        $constraints = $composerJson->getPhpVersionConstraint()
            ->getVersion();

        $requiredPhpExtensions = array_map(
            static fn (Extension $extension): string => (string) $extension,
            iterator_to_array($composerJson->getRequiredPhpExtensions())
        );

        $composerStrategies = [];
        $operatingSystems = [];
        $phpVersions = [];
        $tools = [];

        foreach ($this->automation->toArray() as $automation) {
            if ($automation instanceof ComposerStrategy) {
                $composerStrategies[] = $automation;
                continue;
            }

            if ($automation instanceof Tool) {
                $tools[] = $automation;
                continue;
            }

            if ($automation instanceof PhpVersion) {
                $phpVersions[] = $automation;
                continue;
            }

            if ($automation instanceof OperatingSystem) {
                $operatingSystems[] = $automation;
            }
        }

        foreach ($tools as $toolEnum) {
            $tool = $this->container->get($toolEnum->toString());

            if (! $tool instanceof ToolInterface) {
                continue;
            }

            if (! $tool->isPresent()) {
                continue;
            }

            $name = $tool->name();

            $command = $tool->command();

            $extensions = array_unique([...$requiredPhpExtensions, ...$tool->extensions()]);

            if (
                ! match (true) {
                    $tool instanceof Psalm,
                    $tool instanceof PHPUnit => true,
                    default => false,
                }
            ) {
                $generateMatrixEvent->include(
                    Job::new(
                        $name,
                        $command,
                        $extensions,
                        $composerCacheFilesDirectory,
                        $composerJsonPath,
                        $composerLockPath,
                        PhpVersion::latest(),
                    )
                );
                continue;
            }

            foreach ($phpVersions as $phpVersion) {
                $isPhpVersionExperimental = PhpVersion::isExperimental($phpVersion);
                if ($isPhpVersionExperimental) {
                    continue;
                }

                if (! Semver::satisfies($phpVersion->toString(), $constraints)) {
                    continue;
                }

                foreach ($composerStrategies as $composerStrategy) {
                    $isComposerDependencyExperimental = ComposerStrategy::isExperimental($composerStrategy);

                    foreach ($operatingSystems as $operatingSystem) {
                        $generateMatrixEvent->include(
                            Job::new(
                                $name,
                                $command,
                                $extensions,
                                $composerCacheFilesDirectory,
                                $composerJsonPath,
                                $composerLockPath,
                                $phpVersion,
                                $composerStrategy,
                                $operatingSystem,
                                $isComposerDependencyExperimental,
                            )
                        );
                    }
                }
            }
        }

        $gitHubOutput = $this->environmentVariables->get('GITHUB_OUTPUT', tempnam(sys_get_temp_dir(), 'GITHUB_OUTPUT'));

        $matrix = sprintf('matrix=%s' . PHP_EOL, $generateMatrixEvent->getMatrix());

        file_put_contents($gitHubOutput, $matrix, FILE_APPEND);

        dispatchOutputEvent($matrix);
    }

    public function write(string $message): int
    {
        try {
            dispatchOutputEvent($message);
            return Command::SUCCESS;
        } catch (Throwable) {
            return Command::FAILURE;
        }
    }
}
