<?php declare(strict_types=1);

namespace DumpIt\StashFilter\Application\Filter;

use DumpIt\Shared\Infrastructure\Bus\Command\CommandHandler;
use DumpIt\StashFilter\Domain\Filter\Filter;
use DumpIt\StashFilter\Domain\Filter\FilterMod;
use DumpIt\StashFilter\Domain\Filter\FilterRepositoryInterface;
use DumpIt\StashFilter\Domain\Stash\ModRepositoryInterface;

class EditFilterCommandHandler implements CommandHandler
{
    private FilterRepositoryInterface $filters;

    private ModRepositoryInterface $mods;

    public function __construct(FilterRepositoryInterface $filters, ModRepositoryInterface $mods)
    {
        $this->filters = $filters;
        $this->mods = $mods;
    }

    public function __invoke(EditFilterCommand $command): void
    {
        if (!$this->filters->canUserAccess($command->id(), $command->userId())) {
            throw new \Exception('', 404);
        }

        $filter = $this->filters->byId($command->id());

        $mods = $this->buildMods($filter, $command->mods());

        $this->filters->edit($filter, $command->name(), $mods);
    }

    //TODO Dupped code with FilterRepository::buildMods
    private function buildMods(Filter $filter, array $mods): array
    {
        $modEntities = $this->mods->byIds(array_column($mods, 'id'));

        return array_map(
            function (array $mod) use ($filter, $modEntities) {
                return new FilterMod($filter, $modEntities[$mod['id']], $mod['values'], $mod['condition']);
            },
            $mods,
        );
    }
}
