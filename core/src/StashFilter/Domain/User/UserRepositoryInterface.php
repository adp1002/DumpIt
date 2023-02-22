<?php declare(strict_types=1);

namespace DumpIt\StashFilter\Domain\User;

interface UserRepositoryInterface
{
    public function byId(string $userId): User;

    public function registerUser(string $userId, string $username, string $realm, string $token, string $type): void;
}