<?php declare(strict_types=1);

namespace DumpIt\StashFilter\Application\Stash;

use DumpIt\Shared\Infrastructure\Bus\Command\Command;

class RefreshTabCommand implements Command
{
    private string $tabId;

    private string $userId;

    public function __construct(string $tabId, string $userId)
    {
        $this->tabId = $tabId;
        $this->userId = $userId;
    }

    public function tabId(): string
    {
        return $this->tabId;
    }

    public function userId(): string
    {
        return $this->userId;
    }
}
