<?php declare(strict_types=1);

namespace DumpIt\StashFilter\Application\Stash;

use DumpIt\Shared\Infrastructure\Bus\Query\Query;

class FilterTabQuery implements Query
{
    private string $tabId;

    private string $userId;

    private array $filters;

    public function __construct(string $tabId, string $userId, array $filters)
    {
        $this->tabId = $tabId;
        $this->userId = $userId;
        $this->filters = $filters;
    }

    public function tabId(): string
    {
        return $this->tabId;
    }
    
    public function userId(): string
    {
        return $this->userId;
    }

    public function filters(): array
    {
        return $this->filters;
    }
}
