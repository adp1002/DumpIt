<?php declare(strict_types=1);

namespace DumpIt\StashFilter\Application\Stash;

use DumpIt\Shared\Infrastructure\Bus\Query\QueryHandler;
use DumpIt\StashFilter\Domain\Stash\ItemRepositoryInterface;
use DumpIt\StashFilter\Domain\Stash\TabRepositoryInterface;
use DumpIt\StashFilter\Domain\Stash\TabTransformer;
use League\Fractal\Resource\Item;

class GetTabQueryHandler implements QueryHandler
{
    private TabRepositoryInterface $tabs;
    private ItemRepositoryInterface $items;

    public function __construct(TabRepositoryInterface $tabs, ItemRepositoryInterface $items)
    {
        $this->tabs = $tabs;
        $this->items = $items;
    }

    public function __invoke(GetTabQuery $query): Item
    {
        $tab = $this->tabs->byId($query->tabId());

        if ($tab->userId() !== $query->userId()) {
            throw new \Exception('', 404);
        }
    
        return new Item($tab, new TabTransformer(), 'data');
    }
}
