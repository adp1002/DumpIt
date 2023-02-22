<?php declare(strict_types=1);

namespace DumpIt\StashFilter\Application\Stash;

use DumpIt\Shared\Infrastructure\Bus\Command\CommandHandler;
use DumpIt\StashFilter\Domain\Stash\ModRepositoryInterface;
use DumpIt\StashFilter\Infrastructure\HttpClient\PoeWebsiteHttpClient;

class RefreshModsCommandHandler implements CommandHandler
{
    private PoeWebsiteHttpClient $client;

    private ModRepositoryInterface $mods;

    public function __construct(PoeWebsiteHttpClient $client, ModRepositoryInterface $mods)
    {
        $this->client = $client;
        $this->mods = $mods;
    }

    public function __invoke(RefreshModsCommand $command)
    {
        $mods = $this->client->getMods();

        $this->mods->refresh($mods);
    }
}
