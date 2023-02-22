<?php declare(strict_types=1);

namespace DumpIt\StashFilter\Domain\Stash;

interface ItemRepositoryInterface
{
    public function byTab(string $id): array;

    public function refresh(string $tabId, array $items): void;
}
