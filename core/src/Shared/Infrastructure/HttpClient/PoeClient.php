<?php declare(strict_types=1);

namespace DumpIt\Shared\Infrastructure\HttpClient;

interface PoeClient
{
    //TODO check with poe/api docs and extract common params
    public function isTokenValid(string $username, string $token): string|null;

    public function getLeagues(): array;

    public function getMods(): array;

    public function getTabs(string $poesessid, string $username, string $realm, string $leagueId): array;

    public function getTabItems(string $poesessid, string $username, string $realm, string $leagueId, int $tabIndex): array;
}
