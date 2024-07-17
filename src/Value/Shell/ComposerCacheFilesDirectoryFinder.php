<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Value\Shell;

use Ghostwriter\Compliance\Exception\FailedToFindComposerCacheFilesDirectoryException;
use Ghostwriter\Shell\Interface\ShellInterface;

use function trim;

final readonly class ComposerCacheFilesDirectoryFinder
{
    public function __construct(
        private ShellInterface $shell,
        private ComposerExecutableFinder $composerExecutableFinder,
    ) {
    }

    /**
     * @throws FailedToFindComposerCacheFilesDirectoryException
     */
    public function __invoke(): string
    {
        $result = $this->shell->execute(
            ($this->composerExecutableFinder)(),
            ['config', 'cache-files-dir', '--no-interaction']
        );

        $output = trim($result->stdout());
        if ($output === '' || $result->exitCode() !== 0) {
            throw new FailedToFindComposerCacheFilesDirectoryException($result->stderr());
        }

        return $output;
    }
}
