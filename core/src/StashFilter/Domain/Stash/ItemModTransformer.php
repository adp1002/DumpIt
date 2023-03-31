<?php declare(strict_types=1);

namespace DumpIt\StashFilter\Domain\Stash;

use League\Fractal\TransformerAbstract;

class ItemModTransformer extends TransformerAbstract
{
    public function transform(ItemMod $itemMod): array
    {
        return [
            'mod' => $itemMod->mod()->text(),
            'values' => $itemMod->values(),
        ];
    }
}
