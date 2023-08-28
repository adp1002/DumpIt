<?php declare(strict_types=1);

namespace DumpIt\Api\Entity;
use Doctrine\ORM\Mapping as ORM;
use DumpIt\Api\Repository\UserRepository;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'api_users', schema: 'dumpit')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\Column(name: 'user_id', type: 'uuid')]
    private string $id;

    #[ORM\Column(type: 'string')]
    private string $username;

    #[ORM\Column(type: 'string')]
    private string $token;

    public function __construct(string $id, string $username, string $token)
    {
        $this->id = $id;
        $this->username = $username;
        $this->token = $token;
    }

    public function id(): string
    {
        return $this->id;
    }

	public function getRoles(): array
    {
        return ['ROLE_USER'];
	}
    
    function eraseCredentials()
    {
	}
	
	public function getUserIdentifier(): string
    {
        return $this->username;
	}

	public function getPassword(): ?string {
        return $this->token;
	}

    public function setToken(string $hashedToken)
    {
        $this->token = $hashedToken;
    }
}
