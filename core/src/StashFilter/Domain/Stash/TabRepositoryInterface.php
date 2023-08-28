<?php declare(strict_types=1);

namespace DumpIt\StashFilter\Domain\Stash;

interface TabRepositoryInterface
{
    public function byId(string $id): Tab;

    public function byUser(string $userId): array;

    public function byUserAndLeague(string $userId, string $leagueId): array;

    public function refresh(array $tabs, string $userId, string $leagueId): void;

    public function findAll(): array;

    public function refreshTab(Tab $tab, array $refreshedTab);
}
