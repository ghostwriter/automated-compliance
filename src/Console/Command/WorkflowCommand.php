<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Console\Command;

use Ghostwriter\Compliance\EventDispatcher\Event\CopyWorkflowEvent;
use Override;
use RuntimeException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

use const DIRECTORY_SEPARATOR;
use const PHP_EOL;

#[AsCommand(name: 'workflow', description: 'Creates a "automation.yml" workflow file.',)]
final class WorkflowCommand extends AbstractCommand
{
    #[Override]
    protected function configure(): void
    {
        $this->addArgument(
            'workflow',
            InputArgument::OPTIONAL,
            'Path to store the generated "automation.yml" workflow.',
            '.github/workflows/automation.yml'
        );

        $this->addOption(
            'overwrite',
            'o',
            InputOption::VALUE_NONE,
            'Path to store the generated "automation.yml" workflow.'
        );
    }

    /**
     * Execute the command.
     *
     * @return int 0 if everything went fine, or an exit code
     */
    #[Override]
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $cwd = \getcwd();

        if ($cwd === false) {
            throw new RuntimeException('Cannot determine the current working directory.');
        }

        $workflow = $input->getArgument('workflow');

        if (! \is_string($workflow)) {
            throw new RuntimeException('The "workflow" argument is missing.');
        }

        try {
            $this->eventDispatcher->dispatch(
                CopyWorkflowEvent::new(
                    $cwd . DIRECTORY_SEPARATOR . $workflow,
                    $input->getOption('overwrite') === true
                )
            );
        } catch (Throwable $throwable) {
            $this->symfonyStyle->error(
                \sprintf(
                    '[%s] %s%s%s' . PHP_EOL,
                    $throwable::class,
                    $throwable->getMessage(),
                    PHP_EOL . PHP_EOL,
                    $throwable->getTraceAsString(),
                )
            );

            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}
