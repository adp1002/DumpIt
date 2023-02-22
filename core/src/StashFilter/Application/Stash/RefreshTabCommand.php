<?php declare(strict_types=1);

namespace DumpIt\StashFilter\Application\Stash;

use DumpIt\Shared\Infrastructure\Bus\Command\Command;

class RefreshTabCommand implements Command
{
    private string $tabId;

    private string $userId;

    private string $leagueId;

    private int $tabIndex;

    public function __construct(string $tabId, string $userId, string $leagueId, int $tabIndex)
    {
        $this->tabId = $tabId;
        $this->userId = $userId;
        $this->leagueId = $leagueId;
        $this->tabIndex = $tabIndex;
    }

    public function tabId(): string
    {
        return $this->tabId;
    }

    public function userId(): string
    {
        return $this->userId;
    }

    public function leagueId(): string
    {
        return $this->leagueId;
    }

    public function tabIndex(): int
    {
        return $this->tabIndex;
    }
}
