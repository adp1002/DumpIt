<?php declare(strict_types=1);

namespace DumpIt\StashFilter\Domain\Stash;

interface ModRepositoryInterface
{
    public function findAll(): array;

    public function refresh(array $mods): void;

    /** @return array|Mod[] */
    public function matchByNames(array $names): array;

    /** @return array|Mod[] */
    public function byIds(array $ids): array;
}
