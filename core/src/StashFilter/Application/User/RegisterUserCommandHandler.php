<?php declare(strict_types=1);

namespace DumpIt\StashFilter\Application\User;
use DumpIt\Shared\Infrastructure\Bus\Command\CommandHandler;
use DumpIt\StashFilter\Domain\User\UserRepositoryInterface;
use DumpIt\StashFilter\Infrastructure\HttpClient\PoeWebsiteHttpClient;
use Ramsey\Uuid\Uuid;

class RegisterUserCommandHandler implements CommandHandler
{
    private UserRepositoryInterface $users;

    private PoeWebsiteHttpClient $client;

    public function __construct(UserRepositoryInterface $users, PoeWebsiteHttpClient $client)
    {
        $this->users = $users;
        $this->client = $client;
    }

    public function __invoke(RegisterUserCommand $command)
    {
        $realm = $this->client->isTokenValid($command->username(), $command->token());
        $userId = $command->userId();

        if (null === $realm) {
            throw new \Exception('Invalid token');
        }

        if (null === $userId) {
            $userId = (string) Uuid::uuid4();
        }

        $user = $this->users->registerUser($userId, $command->username(), $realm, $command->token(), $command->type());

        return $user;
    }
}
