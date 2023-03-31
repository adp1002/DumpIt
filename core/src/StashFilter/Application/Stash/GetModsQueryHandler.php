<?php declare(strict_types=1);

namespace DumpIt\StashFilter\Application\Stash;

use DumpIt\Shared\Infrastructure\Bus\Query\QueryHandler;
use DumpIt\StashFilter\Domain\Stash\ModRepositoryInterface;
use DumpIt\StashFilter\Domain\Stash\ModTransformer;
use League\Fractal\Resource\Collection;

class GetModsQueryHandler implements QueryHandler
{
    private ModRepositoryInterface $mods;

    public function __construct(ModRepositoryInterface $mods)
    {
        $this->mods = $mods;
    }

    public function __invoke(GetModsQuery $query): Collection
    {
        return new Collection($this->mods->findAll(), new ModTransformer(), 'data');
    }
}
