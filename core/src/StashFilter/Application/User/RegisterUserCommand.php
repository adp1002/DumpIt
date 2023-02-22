<?php declare(strict_types=1);

namespace DumpIt\StashFilter\Application\User;
use DumpIt\Shared\Infrastructure\Bus\Command\Command;

class RegisterUserCommand implements Command
{
    private $userId;

    private $username;

    private $realm;

    private $token;

    private $type;

    public function __construct($userId, $username, $realm, $token, $type)
    {
        $this->userId = $userId;
        $this->username = $username;
        $this->realm = $realm;
        $this->token = $token;
        $this->type = $type;
    }

    public function userId(): string
    {
        return $this->userId;
    }

    public function username(): string
    {
        return $this->username;
    }

    public function realm(): string
    {
        return $this->realm;
    }

    public function token(): string
    {
        return $this->token;
    }

    public function type(): string
    {
        return $this->type;
    }
}