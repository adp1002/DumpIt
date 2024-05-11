<?php declare(strict_types=1); 

namespace DumpIt\StashFilter\Application\Stash;

use DumpIt\Shared\Infrastructure\Bus\Query\QueryHandler;
use DumpIt\StashFilter\Domain\Stash\TabTransformer;
use League\Fractal\Resource\Collection;
use DumpIt\StashFilter\Domain\Stash\TabRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class GetTabsQueryHandler implements QueryHandler
{
    private TabRepositoryInterface $tabs;

    public function __construct(TabRepositoryInterface $tabs)
    {
        $this->tabs = $tabs;
    }

	public function __invoke(GetTabsQuery $query): Collection
    {
        if (null === $query->leagueId()) {
            $data = $this->tabs->byUser($query->userId());
        } else {
            $data = $this->tabs->byUserAndLeague($query->userId(), $query->leagueId());
        }

        return new Collection($data, new TabTransformer(), 'data');
	}
}
