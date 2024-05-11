<?php declare(strict_types=1);

namespace DumpIt\StashFilter\Domain\Filter;

use League\Fractal\TransformerAbstract;

class FilterModTransformer extends TransformerAbstract
{

    public function transform(FilterMod $filterMod): array
    {
        return [
            'id' => $filterMod->mod()->id(),
            'mod' => $filterMod->mod()->text(),
            'values' => $filterMod->values(),
            'condition' => $filterMod->condition(),
        ];
    }
}
