<?php declare(strict_types=1);

namespace DumpIt\StashFilter\Application\Stash;

use DumpIt\Shared\Infrastructure\Bus\Command\CommandHandler;
use DumpIt\StashFilter\Domain\Stash\LeagueRepositoryInterface;
use DumpIt\StashFilter\Infrastructure\HttpClient\PoeWebsiteHttpClient;

class RefreshLeaguesCommandHandler implements CommandHandler
{
    private PoeWebsiteHttpClient $poeWebsiteHttpClient;

    private LeagueRepositoryInterface $leagues;

    public function __construct(PoeWebsiteHttpClient $poeWebsiteHttpClient, LeagueRepositoryInterface $leagues)
    {
        $this->poeWebsiteHttpClient = $poeWebsiteHttpClient;
        $this->leagues = $leagues;
    }

    public function __invoke(RefreshLeaguesCommand $command)
    {
        $leagues = $this->poeWebsiteHttpClient->getLeagues();

        $this->leagues->refresh($leagues);
    }
}
