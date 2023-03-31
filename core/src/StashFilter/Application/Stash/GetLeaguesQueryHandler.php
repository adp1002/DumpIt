<?php declare(strict_types=1);

namespace DumpIt\StashFilter\Application\Stash;

use DumpIt\Shared\Infrastructure\Bus\Query\QueryHandler;
use DumpIt\StashFilter\Domain\Stash\LeagueRepositoryInterface;
use DumpIt\StashFilter\Domain\Stash\LeagueTransformer;
use League\Fractal\Resource\Collection;

class GetLeaguesQueryHandler implements QueryHandler
{
    private LeagueRepositoryInterface $leagues;

    public function __construct(LeagueRepositoryInterface $leagues)
    {
        $this->leagues = $leagues;
    }

    public function __invoke(GetLeaguesQuery $query): Collection
    {
        return new Collection($this->leagues->findAll(), new LeagueTransformer(), 'data');
    }
}
