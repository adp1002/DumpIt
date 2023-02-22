<?php declare(strict_types=1);

namespace DumpIt\StashFilter\Domain\Stash;

interface TabRepositoryInterface
{
    public function byId(string $id): Tab;

    public function byUser(string $userId): array;

    public function byUserAndLeague(string $userId, string $leagueId): array;
}
