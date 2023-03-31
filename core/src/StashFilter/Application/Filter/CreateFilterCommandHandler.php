<?php declare(strict_types=1);

namespace DumpIt\StashFilter\Application\Filter;

use DumpIt\Shared\Infrastructure\Bus\Command\CommandHandler;
use DumpIt\StashFilter\Domain\Filter\FilterRepositoryInterface;
use DumpIt\StashFilter\Domain\User\UserRepositoryInterface;

class CreateFilterCommandHandler implements CommandHandler
{
    private FilterRepositoryInterface $filters;

    private UserRepositoryInterface $users;

    public function __construct(FilterRepositoryInterface $filters, UserRepositoryInterface $users)
    {
        $this->filters = $filters;
        $this->users = $users;
    }

    public function __invoke(CreateFilterCommand $command): void
    {
        $user = $this->users->byId($command->userId());

        $this->filters->create($command->name(), $user->id(), $command->mods());
    }
}
