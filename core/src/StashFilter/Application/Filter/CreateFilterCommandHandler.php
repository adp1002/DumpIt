<?php declare(strict_types=1);

namespace DumpIt\StashFilter\Application\Filter;

use DumpIt\Shared\Infrastructure\Bus\Command\CommandHandler;
use DumpIt\StashFilter\Domain\Filter\FilterMod;
use DumpIt\StashFilter\Domain\Filter\FilterRepositoryInterface;
use DumpIt\StashFilter\Domain\Stash\ModRepositoryInterface;
use DumpIt\StashFilter\Domain\User\UserRepositoryInterface;
use Ramsey\Uuid\Uuid;

class CreateFilterCommandHandler implements CommandHandler
{
    private FilterRepositoryInterface $filters;

    private UserRepositoryInterface $users;

    private ModRepositoryInterface $mods;

    public function __construct(FilterRepositoryInterface $filters, UserRepositoryInterface $users, ModRepositoryInterface $mods)
    {
        $this->filters = $filters;
        $this->users = $users;
        $this->mods = $mods;
    }

    public function __invoke(CreateFilterCommand $command): void
    {
        $user = $this->users->byId($command->userId());

        $filterId = (string) Uuid::uuid4();

        $mods = $this->buildMods($filterId, $command->mods());

        $this->filters->create($filterId, $command->name(), $mods, $user);
    }

    private function buildMods(string $filterId, array $mods): array
    {
        return array_map(
            function (array $mod) use ($filterId) {
                $mod = $this->mods->byId($mod['modId']);
            
                return new FilterMod($filterId, $mod, $mod['values'], $mod['condition']);
            },
            $mods,
        );
    }
}
