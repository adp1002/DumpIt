<?php declare(strict_types=1);

namespace DumpIt\StashFilter\Domain\Stash;

use League\Fractal\TransformerAbstract;

class TabTransformer extends TransformerAbstract
{
    protected array $availableIncludes = ['items'];

    public function transform(Tab $tab): array
    {
        return [
            'id' => $tab->id(),
            'name' => $tab->name(),
            'league' => $tab->league(),
        ];
    }

    public function includeItems(Tab $tab)
    {
        return $this->collection($tab->items(), new ItemTransformer());
    }
}
