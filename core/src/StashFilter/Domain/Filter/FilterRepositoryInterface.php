<?php declare(strict_types=1);

namespace DumpIt\StashFilter\Domain\Filter;

interface FilterRepositoryInterface
{
    public function byId(string $id): Filter;

    public function byUser(string $userId): array;

    public function create(string $name, string $userId, array $mods): void;

    public function edit(Filter $filter, ?string $name, ?array $mods): void;

    public function delete(string $id): void;

    public function canUserAccess(string $id, string $userId): bool;
}
