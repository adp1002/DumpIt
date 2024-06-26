<?php declare(strict_types=1);

namespace DumpIt\StashFilter\Domain\Stash;

use League\Fractal\TransformerAbstract;

class ItemTransformer extends TransformerAbstract
{
    protected array $defaultIncludes = ['mods'];

    public function transform(Item $item): array
    {
        return [
            'id' => $item->id(),
            'name' => $item->name(),
            'baseType' => $item->baseType(),
        ];
    }

    public function includeMods(Item $item)
    {
        return $this->collection($item->mods(), new ItemModTransformer());
    }
}
