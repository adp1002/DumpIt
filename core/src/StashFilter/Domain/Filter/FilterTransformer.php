<?php declare(strict_types=1);

namespace DumpIt\StashFilter\Domain\Filter;

use League\Fractal\TransformerAbstract;

class FilterTransformer extends TransformerAbstract
{
    protected array $availableIncludes = ['mods'];

    public function transform(Filter $filter): array
    {
        return [
            'id' => $filter->id(),
            'name' => $filter->name(),
        ];
    }

    public function includeMods(Filter $filter)
    {
        return $this->collection($filter->mods(), new FilterModTransformer());
    }
}
