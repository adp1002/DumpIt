<?php declare(strict_types=1);

namespace DumpIt\StashFilter\Application\Filter;

use DumpIt\Shared\Infrastructure\Bus\Query\QueryHandler;
use DumpIt\StashFilter\Domain\Filter\FilterRepositoryInterface;
use DumpIt\StashFilter\Domain\Filter\FilterTransformer;
use League\Fractal\Resource\Collection;

class GetFiltersQueryHandler implements QueryHandler
{
    private FilterRepositoryInterface $filters;

    public function __construct(FilterRepositoryInterface $filters)
    {
        $this->filters = $filters;
    }

    public function __invoke(GetFiltersQuery $query): Collection
    {
        return new Collection($this->filters->byUser($query->userId()), new FilterTransformer(), 'data');
    }
}
