<?php declare(strict_types=1);

namespace DumpIt\StashFilter\Application\Stash;

use DumpIt\Shared\Infrastructure\Bus\Command\Command;

class RefreshTabsCommand implements Command
{
    private $userId;

    private $leagueId;

    public function __construct($userId, $leagueId)
    {
        $this->userId = $userId;
        $this->leagueId = $leagueId;
    }

    public function userId(): string
    {
        return $this->userId;
    }

    public function leagueId(): string
    {
        return $this->leagueId;
    }
}
