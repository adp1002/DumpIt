<?php declare(strict_types=1);

namespace DumpIt\StashFilter\Application\Stash;

use DumpIt\Shared\Infrastructure\Bus\Command\CommandHandler;
use DumpIt\StashFilter\Domain\Stash\TabRepositoryInterface;
use DumpIt\StashFilter\Domain\User\UserRepositoryInterface;
use DumpIt\StashFilter\Infrastructure\HttpClient\PoeWebsiteHttpClient;

class RefreshTabsCommandHandler implements CommandHandler
{
    private TabRepositoryInterface $tabs;

    private PoeWebsiteHttpClient $client;

    private UserRepositoryInterface $users;

    public function __construct(TabRepositoryInterface $tabs, PoeWebsiteHttpClient $client, UserRepositoryInterface $users)
    {
        $this->tabs = $tabs;
        $this->client = $client;
        $this->users = $users;
    }

    
    public function __invoke(RefreshTabsCommand $command)
    {
        $user = $this->users->byId($command->userId());

        $tabs = $this->client->getTabs(
            $user->token(),
            $user->username(),
            $user->realm(),
            $command->leagueId()
        );

        $this->tabs->refreshAll($tabs);
    }
}
