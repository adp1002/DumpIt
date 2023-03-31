<?php declare(strict_types=1);

namespace DumpIt\Api\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use DumpIt\Api\Entity\User;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserRepository extends ServiceEntityRepository implements UserLoaderInterface
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(ManagerRegistry $managerRegistry, UserPasswordHasherInterface $passwordHasher)
    {
        parent::__construct($managerRegistry, User::class);

        $this->passwordHasher = $passwordHasher;
    }

	public function loadUserByIdentifier(string $identifier): ?User
    {
        return $this->findBy(['username' => $identifier])[0];
	}

    public function registerUser($id, $username, $token)
    {
        $user = new User($id, $username, $token);

        $hashedToken = $this->passwordHasher->hashPassword($user, $token);

        $user->setToken($hashedToken);

        $this->_em->persist($user);
        $this->_em->flush();
    }
}
