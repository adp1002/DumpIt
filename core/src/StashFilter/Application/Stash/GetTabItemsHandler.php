<?php declare(strict_types=1);

namespace DumpIt\StashFilter\Application\Stash;

use DumpIt\Shared\Infrastructure\Bus\Query\QueryHandler;
use DumpIt\StashFilter\Domain\Stash\ItemRepositoryInterface;
use DumpIt\StashFilter\Domain\Stash\ItemTransformer;
use League\Fractal\Resource\Collection;

class GetTabItemsHandler implements QueryHandler
{
    private ItemRepositoryInterface $items;

    public function __construct(ItemRepositoryInterface $items)
    {
        $this->items = $items;
    }

    public function __invoke(GetTabItemsQuery $query): Collection
    {
        return new Collection($this->items->byTab($query->tabId()), new ItemTransformer());
    }
}
