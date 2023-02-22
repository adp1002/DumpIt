<?php declare(strict_types=1);

namespace DumpIt\StashFilter\Application\Stash;

use DumpIt\Shared\Infrastructure\Bus\Command\CommandHandler;
use DumpIt\StashFilter\Domain\Stash\Item;
use DumpIt\StashFilter\Domain\Stash\ItemMod;
use DumpIt\StashFilter\Domain\Stash\ItemRepositoryInterface;
use DumpIt\StashFilter\Domain\Stash\ModRepositoryInterface;
use DumpIt\StashFilter\Domain\User\UserRepositoryInterface;
use DumpIt\StashFilter\Infrastructure\HttpClient\PoeWebsiteHttpClient;

class RefreshTabCommandHandler implements CommandHandler
{
    private ItemRepositoryInterface $items;

    private PoeWebsiteHttpClient $client;

    private UserRepositoryInterface $users;

    private ModRepositoryInterface $mods;

    public function __construct(ItemRepositoryInterface $items, PoeWebsiteHttpClient $client, UserRepositoryInterface $users, ModRepositoryInterface $mods)
    {
        $this->items = $items;
        $this->client = $client;
        $this->users = $users;
        $this->mods = $mods;
    }

    public function __invoke(RefreshTabCommand $command): void
    {
        $user = $this->users->byId($command->userId());

        $items = $this->client->getTabItems($user->token(), $user->username(), $user->realm(), $command->leagueId(), $command->tabIndex());

        $items = $this->buildItems($items, $command->tabId());

        $this->items->refreshForTab($command->tabId(), $items);
    }

    private function parseModValues(string $mod): array
    {
        preg_match('/[+-]?\d+/', $mod, $values);

        $textA = preg_replace('/[+-]?\d+/', '#', $mod);

        $pos = [];

        for ($i = 0; $i < strlen($textA); $i++){
            if ('#' === $textA[$i]) {
                $pos[] = $i;
            }
        }

        return array_combine($pos, $values);
    }

    private function buildItems($items, $tabId): array
    {
        return array_map(
            function (array $item) use ($tabId) {
                $itemMods = [];

                $mods = $this->mods->matchByNames($item['mods']);

                foreach ($item['mods'] as $mod) {
                    $totalValues = $this->parseModValues($mod);

                    $modConfidence = -1;
                    $actualMod = null;

                    foreach ($mods as $modObject) {
                        $match = similar_text($modObject->text(), $mod) / strlen($modObject->text());

                        if ($match > $modConfidence) {
                            $actualMod = $modObject;
                        }
                    }

                    $modText = $actualMod->text();
                    $actualValues = [];

                    for ($i = 0; $i < strlen($modText); $i++) {
                        if ('#' === $modText[$i]) {
                            $actualValues[] = $totalValues[$i];
                        }
                    }

                    $itemMods[] = new ItemMod($item['id'], $actualMod, $actualValues);
                }
                

                return new Item($item['id'], $item['name'], $item['ilvl'], $item['baseType'], $tabId, $itemMods);
            },
            $items,
        );
    }
}
