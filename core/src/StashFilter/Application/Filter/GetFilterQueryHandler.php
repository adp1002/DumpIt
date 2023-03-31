<?php declare(strict_types=1);

namespace DumpIt\StashFilter\Application\Filter;

use DumpIt\Shared\Infrastructure\Bus\Query\QueryHandler;
use DumpIt\StashFilter\Domain\Filter\FilterRepositoryInterface;
use DumpIt\StashFilter\Domain\Filter\FilterTransformer;
use League\Fractal\Resource\Item;

class GetFilterQueryHandler implements QueryHandler
{
    private FilterRepositoryInterface $filters;

    public function __construct(FilterRepositoryInterface $filters)
    {
        $this->filters = $filters;
    }

    public function __invoke(GetFilterQuery $query): Item
    {
        if (!$this->filters->canUserAccess($query->id(), $query->userId())) {
            throw new \Exception('', 404);
        }

        return new Item($this->filters->byId($query->id()), new FilterTransformer(), 'data');
    }
}
