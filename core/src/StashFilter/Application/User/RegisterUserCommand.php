<?php declare(strict_types=1);

namespace DumpIt\StashFilter\Application\User;

use DumpIt\Shared\Infrastructure\Bus\Command\Command;

class RegisterUserCommand implements Command
{
    private string $username;

    private string $token;

    private string $type;

    private string|null $userId;
   
    public function __construct(string $username, string $token, string $type, string|null $userId = null)
    {
        $this->username = $username;
        $this->token = $token;
        $this->type = $type;
        $this->userId = $userId;
    }

    public function username(): string
    {
        return $this->username;
    }

    public function token(): string
    {
        return $this->token;
    }

    public function type(): string
    {
        return $this->type;
    }

    public function userId(): string|null
    {
        return $this->userId;
    }
}
