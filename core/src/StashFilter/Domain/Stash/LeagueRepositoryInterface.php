<?php declare(strict_types=1);

namespace DumpIt\StashFilter\Domain\Stash;

interface LeagueRepositoryInterface
{
    public function refresh(array $leagues): void;
}
