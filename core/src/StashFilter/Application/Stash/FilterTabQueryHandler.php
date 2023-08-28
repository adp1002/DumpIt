<?php declare(strict_types=1);

namespace DumpIt\StashFilter\Application\Stash;

use DumpIt\Shared\Infrastructure\Bus\Query\QueryHandler;
use DumpIt\StashFilter\Domain\Filter\Filter;
use DumpIt\StashFilter\Domain\Filter\FilterMod;
use DumpIt\StashFilter\Domain\Filter\FilterRepositoryInterface;
use DumpIt\StashFilter\Domain\Stash\Item;
use DumpIt\StashFilter\Domain\Stash\ItemTransformer;
use DumpIt\StashFilter\Domain\Stash\Tab;
use DumpIt\StashFilter\Domain\Stash\TabRepositoryInterface;
use League\Fractal\Resource\Collection;

class FilterTabQueryHandler implements QueryHandler
{
    private TabRepositoryInterface $tabs;
    
    private FilterRepositoryInterface $filters;

    public function __construct(TabRepositoryInterface $tabs, FilterRepositoryInterface $filters)
    {
        $this->tabs = $tabs;
        $this->filters = $filters;
    }

    public function __invoke(FilterTabQuery $query): Collection
    {
        foreach ($query->filters() as $filterId) {
            if (!$this->filters->canUserAccess($filterId, $query->userId())) {
                throw new \Exception('', 404);
            }
        }

        $items = $this->associativeItems($this->tabs->byId($query->tabId()));
        $filters = $this->associativeFilters($this->filters->byIds($query->filters()));
        $filteredItems = [];

        foreach ($filters as $filter) {
            foreach ($items as $item) {
                foreach ($filter->mods() as $modId => $mod) {
                    if (!$item->mods()->containsKey($modId)) {
                        break;
                    }

                    $itemValues = $item->mods()->get($modId)->values();
                    $filterValues = $mod->values();

                    if (count($itemValues) !== count($filterValues)) {
                        throw new \Exception('', 404);
                    }

                    for ($i = 0; $i < count($mod->values()); $i++) {
                        $isValid = match ($mod->condition()) {
                            FilterMod::EQUAL => $itemValues[$i] === $filterValues[$i],
                            FilterMod::GREATER_THAN => $itemValues[$i] > $filterValues[$i],
                            FilterMod::GREATER_THAN_OR_EQUAL => $itemValues[$i] >= $filterValues[$i],
                            FilterMod::LESSER_THAN => $itemValues[$i] < $filterValues[$i],
                            FilterMod::LESSER_THAN_OR_EQUAL => $itemValues[$i] <= $filterValues[$i],
                            default => throw new \Exception(),
                        };

                        if (!$isValid) {
                            break;
                        }
                    }

                    $filteredItems[] = $item;
                }
            }

            $items = $filteredItems;
            $filteredItems = [];
        }

        return new Collection($items, new ItemTransformer(), 'data');
    }

    /** @return Item[] */
    private function associativeItems(Tab $tab): array
    {
        $items = [];

        foreach ($tab->items() as $item) {
            $mods = [];
            
            foreach ($item->mods() as $mod) {
                $mods[$mod->mod()->id()] = $mod;
            }

            $item->changeMods($mods);

            $items[$item->id()] = $item;
        }

        return $items;
    }

    /** @return Filter[] */
    private function associativeFilters(array $filters): array
    {
        $associativeFilters = [];

        foreach ($filters as $filter) {
            $mods = [];

            foreach ($filter->mods() as $mod) {
                $mods[$mod->mod()->id()] = $mod;
            }

            $filter->changeMods($mods);

            $associativeFilters[] = $filter;
        }

        return $associativeFilters;
    }
}
