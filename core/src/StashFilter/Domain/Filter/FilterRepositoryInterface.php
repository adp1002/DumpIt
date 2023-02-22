<?php declare(strict_types=1);

namespace DumpIt\StashFilter\Domain\Filter;

use Doctrine\Common\Collections\Collection;
use DumpIt\StashFilter\Domain\User\User;

interface FilterRepositoryInterface
{
    public function byId(string $id): Filter;

    public function byUser(string $userId): array;

    public function create(string $id, string $name, array $mods, User $user): void;

    public function edit(string $id, ?string $name, ?Collection $mods): void;

    public function delete(string $id): void;
}
