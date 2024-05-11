<?php declare(strict_types=1);

namespace DumpIt\StashFilter\Application\Stash;

use DumpIt\Shared\Infrastructure\Bus\Query\Query;

class GetTabsQuery implements Query
{
    private string $userId;

    private ?string $leagueId;

    public function __construct(string $userId, ?string $leagueId)
    {
        $this->userId = $userId;
        $this->leagueId = $leagueId;
    }

    public function userId(): string
    {
        return $this->userId;
    }

    public function leagueId(): ?string
    {
        return $this->leagueId;
    }
}
