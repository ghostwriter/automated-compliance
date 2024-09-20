<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Value\GitHub\Action;

use Ghostwriter\Compliance\Enum\ComposerStrategy;
use Ghostwriter\Compliance\Enum\OperatingSystem;
use Ghostwriter\Compliance\Enum\PhpVersion;

final readonly class Job
{
    /**
     * @param array<string> $extensions
     */
    public function __construct(
        private string $name,
        private string $command,
        private array $extensions,
        private string $composerCacheFilesDirectory,
        private string $composerJsonPath,
        private string $composerLockPath,
        private ComposerStrategy $composerStrategy,
        private PhpVersion $phpVersion,
        private OperatingSystem $operatingSystem,
        private bool $experimental,
    ) {
    }

    /**
     * @return array{
     *     name:string,
     *     runCommand:string,
     *     installCommand:string,
     *     validateCommand:string,
     *     composerCacheFilesDirectory:string,
     *     extensions:array<string>,
     *     os:string,
     *     php:string,
     *     dependency:string,
     *     experimental:bool
     * }
     */
    public function toArray(): array
    {
        $composerOptions = ['--no-interaction', '--no-progress', '--ansi'];

        $composerCommand = match ($this->composerStrategy) {
            ComposerStrategy::LOCKED => 'install',
            default => 'update',
        };

        if ($this->composerStrategy === ComposerStrategy::LOWEST) {
            $composerOptions[] = '--prefer-lowest';
            $composerOptions[] = '--prefer-stable';
        }

        if (! \file_exists($this->composerLockPath)) {
            $composerCommand = 'update';
        }

        $validateCommand = \file_exists($this->composerJsonPath) ?
            // 'composer validate --no-check-publish --no-check-lock --no-interaction --ansi --strict' :
            'composer validate --no-check-publish --no-check-lock --no-interaction --ansi --strict || exit 0;' :
            'echo "composer.json does not exist" && exit 1;';

        return [
            'name' => $this->name,
            'runCommand' => $this->command,
            'composerCacheFilesDirectory' => $this->composerCacheFilesDirectory,
            'os' => $this->operatingSystem->toString(),
            'php' => $this->phpVersion->toString(),
            'dependency' => $this->composerStrategy->toString(),
            'experimental' => $this->experimental,
            'extensions' => $this->extensions,
            'validateCommand' => $validateCommand,
            'installCommand' => \sprintf('composer %s %s', $composerCommand, \implode(' ', $composerOptions)),
        ];
    }

    /**
     * @param array<string> $extensions
     */
    public static function new(
        string $name,
        string $command,
        array $extensions,
        string $composerCacheFilesDirectory,
        string $composerJsonPath,
        string $composerLockPath,
        PhpVersion $phpVersion,
        ComposerStrategy $composerStrategy = ComposerStrategy::LOCKED,
        OperatingSystem $operatingSystem = OperatingSystem::UBUNTU,
        bool $experimental = false,
    ): self {
        return new self(
            name: $name,
            command: $command,
            extensions: $extensions,
            composerCacheFilesDirectory: $composerCacheFilesDirectory,
            composerJsonPath: $composerJsonPath,
            composerLockPath: $composerLockPath,
            composerStrategy: $composerStrategy,
            phpVersion: $phpVersion,
            operatingSystem: $operatingSystem,
            experimental: $experimental,
        );
    }

    public static function noop(): self
    {
        $name = 'Noop';
        $currentDirectory = \getcwd() ?: '.';
        return new self(
            name: $name,
            command: \sprintf('echo "%s"', $name),
            extensions: [],
            composerCacheFilesDirectory: '/home/runner/.cache/composer/files',
            composerJsonPath: $currentDirectory,
            composerLockPath: $currentDirectory,
            composerStrategy: ComposerStrategy::LOCKED,
            phpVersion: PhpVersion::latest(),
            operatingSystem: OperatingSystem::UBUNTU,
            experimental: true,
        );
    }
}
