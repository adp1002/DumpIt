<?php declare(strict_types=1);

namespace DumpIt\StashFilter\Domain\User;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'users', schema: 'dumpit')]
class User
{
    public const API = 'api';
    public const POESESSID = 'poesessid';
    public const PC = 'pc';

    private const ALLOWED_TYPES = [self::API, self::POESESSID];

    #[ORM\Id]
    #[ORM\Column(type: "uuid")]
    private string $id;

    #[ORM\Column(type: "string")]
    private string $username;

    #[ORM\Column(type: "string")]
    private string $realm;

    #[ORM\Column(type: "string")]
    private string $token;

    #[ORM\Column(type: "string")]
    private string $type;

    public function __construct($id, $username, $realm, $token, $type)
    {
        $this->id = $id;
        $this->username = $username;
        $this->realm = $realm;
        $this->token = $token;

        $this->changeType($type);
    }

    public function id(): string
    {
        return $this->id;
    }

    public function token(): string
    {
        return $this->token;
    }

    public function realm(): string
    {
        return $this->realm;
    }

    public function type(): string
    {
        return $this->type;
    }

    public function username(): string
    {
        return $this->username;
    }

    public function changeType(string $type): self
    {
        if (!in_array($type, self::ALLOWED_TYPES)) {
            throw new \Exception();
        }

        $this->type = $type;

        return $this;
    }

    public function changeToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }
}
