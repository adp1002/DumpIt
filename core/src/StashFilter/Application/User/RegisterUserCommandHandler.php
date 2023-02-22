<?php declare(strict_types=1);

namespace DumpIt\StashFilter\Application\User;
use DumpIt\Shared\Infrastructure\Bus\Command\CommandHandler;
use DumpIt\StashFilter\Domain\User\UserRepositoryInterface;

class RegisterUserCommandHandler implements CommandHandler
{
    private UserRepositoryInterface $users;

    public function __construct(UserRepositoryInterface $users)
    {
        $this->users = $users;
    }

    public function __invoke(RegisterUserCommand $command)
    {
        $this->users->registerUser($command->userId(), $command->username(), $command->realm(), $command->token(), $command->type());
    }
}