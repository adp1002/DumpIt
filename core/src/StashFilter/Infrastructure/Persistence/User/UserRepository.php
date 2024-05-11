<?php declare(strict_types=1);

namespace DumpIt\StashFilter\Infrastructure\Persistence\User;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use DumpIt\StashFilter\Domain\User\User;
use DumpIt\StashFilter\Domain\User\UserRepositoryInterface;

class UserRepository extends ServiceEntityRepository implements UserRepositoryInterface
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, User::class);
    }

    public function byId(string $userId): User
    {
        return $this->find($userId);
    }

    public function registerUser(string $userId, string $username, string $realm, string $token, string $type): User
    {
        $user = $this->findOneBy(['username' => $username]);

        if (null !== $user) {
            return $user;
        }

        $user = new User($userId, $username, $realm, $token, $type);

        $this->_em->persist($user);
        $this->_em->flush();

        return $user;
    }
}
