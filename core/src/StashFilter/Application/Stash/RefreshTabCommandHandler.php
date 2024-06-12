<?php declare(strict_types=1);

namespace DumpIt\StashFilter\Application\Stash;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use DumpIt\Shared\Infrastructure\Bus\Command\CommandHandler;
use DumpIt\StashFilter\Domain\Stash\Item;
use DumpIt\StashFilter\Domain\Stash\ItemMod;
use DumpIt\StashFilter\Domain\Stash\ItemRepositoryInterface;
use DumpIt\StashFilter\Domain\Stash\ModRepositoryInterface;
use DumpIt\StashFilter\Domain\Stash\TabRepositoryInterface;
use DumpIt\StashFilter\Domain\User\UserRepositoryInterface;
use DumpIt\StashFilter\Infrastructure\HttpClient\PoeWebsiteHttpClient;

class RefreshTabCommandHandler implements CommandHandler
{
    private ItemRepositoryInterface $items;

    private PoeWebsiteHttpClient $client;

    private UserRepositoryInterface $users;

    private ModRepositoryInterface $mods;

    private TabRepositoryInterface $tabs;

    public function __construct(ItemRepositoryInterface $items, PoeWebsiteHttpClient $client, UserRepositoryInterface $users, ModRepositoryInterface $mods, TabRepositoryInterface $tabs)
    {
        $this->items = $items;
        $this->client = $client;
        $this->users = $users;
        $this->mods = $mods;
        $this->tabs = $tabs;
    }

    public function __invoke(RefreshTabCommand $command): void
    {
        $tab = $this->tabs->byId($command->tabId());

        if ($tab->userId() !== $command->userId()) {
            throw new \Exception();
        }

        $user = $this->users->byId($command->userId());
        
        $refreshedTab = $this->client->getTabWithItems($user->token(), $user->username(), $user->realm(), $tab->leagueId(), $tab->index());

        if ($tab->id() !== $refreshedTab['id']) {
            throw new \Exception();
        }

        $refreshedTab['items'] = $this->buildItems($refreshedTab['items'], $tab);

        $this->tabs->refreshTab($tab, $refreshedTab);
    }

    private function parseModValues(string $mod): array
    {
        $values = [];

        preg_match_all('/-?\d+/', $mod, $values);

        $textA = preg_replace('/\d+/', '#', $mod);

        $pos = [];

        for ($i = 0; $i < strlen($textA); $i++){
            if ('#' === $textA[$i]) {
                $pos[] = $i;
            }
        }

        return array_combine($pos, $values[0]);
    }

    private function buildItems($items, $tab): array
    {
        return array_map(
            function (array $item) use ($tab) {
                $itemEntity = new Item($item['id'], $item['name'], $item['ilvl'], $item['baseType'], $tab, []);

                $itemMods = [];

                $mods = $this->mods->matchByNames($item['mods']);

                //TODO Some mods might not get recognized, 
                // rn they will be ignored but they should be
                // logged to be dealt with properly

                foreach ($mods as $mod) {
                    $modConfidence = -1;
                    $actualMod = null;

                    foreach ($item['mods'] as $modObject) {
                        $match = similar_text($mod->text(), $modObject) / strlen($mod->text());

                        if ($match > $modConfidence) {
                            $modConfidence = $match;
                            $actualMod = $modObject;
                        }
                    }

                    $totalValues = $this->parseModValues($actualMod);


                    $modText = $mod->text();
                    $actualValues = [];

                    for ($i = 0; $i < strlen($modText); $i++) {
                        if ('#' === $modText[$i]) {
                            $actualValues[] = $totalValues[$i];
                        }
                    }

                    $itemMods[] = (new ItemMod($itemEntity, $mod, $actualValues));
                }

                $itemEntity->changeMods($itemMods);
                
                return $itemEntity;

                // TODO Doctrine is trying to persist the mods first so the FK is wrong
                // return new Item($item['id'], $item['name'], $item['ilvl'], $item['baseType'], $tabId, $itemMods);
            },
            $items,
        );
    }
}
