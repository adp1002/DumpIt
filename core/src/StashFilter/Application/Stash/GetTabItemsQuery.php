<?php declare(strict_types=1);

namespace DumpIt\StashFilter\Application\Stash;

use DumpIt\Shared\Infrastructure\Bus\Query\Query;

class GetTabItemsQuery implements Query
{
    private string $tabId;

    public function __construct(string $tabId)
    {
        $this->tabId = $tabId;
    }

    public function tabId(): string
    {
        return $this->tabId;
    }
}
