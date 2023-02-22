<?php declare(strict_types=1); 

namespace DumpIt\StashFilter\Application\Stash;

use DumpIt\Shared\Infrastructure\Bus\Query\QueryHandler;
use DumpIt\StashFilter\Domain\Stash\Tab;
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

	public function __invoke(GetTabsQuery $getTabsQuery): Collection
    {
        return new Collection($this->tabs->byUser($getTabsQuery->userId()), new TabTransformer());
	}
}
