<?php declare(strict_types=1);

namespace DumpIt\Api\Command;

use DumpIt\StashFilter\Application\Stash\RefreshLeaguesCommand as RefreshLeaguesDomainCommand;
use DumpIt\Shared\Infrastructure\Bus\Command\CommandBus;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'dumpit:leagues:refresh')]
class RefreshLeaguesCommand extends Command
{
    private CommandBus $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->commandBus->dispatch(new RefreshLeaguesDomainCommand());

        return Command::SUCCESS;
    }
}
