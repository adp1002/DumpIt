<?php declare(strict_types=1);

namespace DumpIt\StashFilter\Infrastructure\HttpClient;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class PoeWebsiteHttpClient
{
    public const ACCOUNT_URL = 'https://www.pathofexile.com/my-account';
    public const LEAGUE_URL = 'https://www.pathofexile.com/api/leagues';
    public const MODS_URL = 'https://www.pathofexile.com/api/trade/data/stats';
    public const TABS_URL = 'https://www.pathofexile.com/character-window/get-stash-items?accountName=%s&realm=%s&league=%s&tabs=1&tabIndex=0&public=true';
    public const TAB_ITEMS_URL = 'https://www.pathofexile.com/character-window/get-stash-items?accountName=%s&realm=%s&league=%s&tabs=1&tabIndex=%d';

    public const REMOVE_ONLY_TAB = '(Remove-only)';
    
    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function isTokenValid(string $username, string $token): string|null
    {
        $response = $this->client->request(
            'GET',
            self::ACCOUNT_URL,
            $this->headers($token),
        );

        if (Response::HTTP_UNAUTHORIZED === $response->getStatusCode()) {
            return null;
        }

        $matches = [];

        preg_match_all(
            '/((?<=A\({"name":")\w+)|((?<=,"realm":")\w+)/',
            $response->getContent(),
            $matches,
        );

        [$requestUsername, $realm] = $matches[0];

        if ($username !== $requestUsername) {
            return null;
        }

        return $realm;
    }

    public function getLeagues(): array
    {
        $leagues = $this->client->request('GET', self::LEAGUE_URL)->toArray();

        return array_map(
            function (array $league) {
                return [
                    'id' => $league['id'],
                    'realm' => $league['realm'],
                ];
            },
            $leagues,
        );
    }

    public function getMods(): array
    {
        $mods = $this->client->request('GET', self::MODS_URL)->toArray();

        foreach ($mods['result'] as $mod) {
            if ('Explicit' === $mod['label']) {
                $mods = $mod['entries'];
                break;
            }
        }

        return array_map(
            function (array $mod) {
                return [
                    'id' => $mod['id'],
                    'text' => $mod['text'],
                ];
            },
            $mods,
        ); 
    }

    public function getTabs(string $poesessid, string $username, string $realm, string $leagueId): array
    {
        $tabsInfo = $this->client->request(
            'GET',
            sprintf(
                self::TABS_URL,
                $username,
                $realm,
                $leagueId,
            ),
            $this->headers($poesessid),
        )->toArray();

        $tabs = array_filter(
            $tabsInfo['tabs'],
            function (array $tab) {
                if (str_contains($tab['n'], self::REMOVE_ONLY_TAB)) {
                    return false;
                }

                return true;
            }
        );

        $indexedTabs = [];

        foreach ($tabs as $tab) {
            $indexedTabs[$tab['id']] = [
                'id' => $tab['id'],
                'name' => $tab['n'],
                'index' => $tab['i'],
            ];
        }

        return $indexedTabs;
    }

    public function getTabWithItems(string $poesessid, string $username, string $realm, string $leagueId, int $tabIndex): array
    {
        $tabs = $this->client->request(
            'GET',
            sprintf(
                self::TAB_ITEMS_URL,
                $username,
                $realm,
                $leagueId,
                $tabIndex
            ),
            $this->headers($poesessid),
        )->toArray();

        $tab = null;

        foreach ($tabs['tabs'] as $rawTab) {
            if ($rawTab['selected']) {
                $tab = [
                    'id' => $rawTab['id'],
                    'name' => $rawTab['n'],
                    'index' => $rawTab['i'],
                ];
            }
        }

        $items = array_filter(
            $tabs['items'],
            function (array $item) {
                return $item['identified'];
            },
        );

        $tab['items'] = array_map(
            function (array $item) {
                return [
                    'id' => $item['id'],
                    'name' => $item['name'],
                    'mods' => $item['explicitMods'],
                    'ilvl' => $item['ilvl'],
                    'baseType' => $item['baseType'],
                ];
            },
            $items,
        );

        return $tab;
    }

    private function headers(string|null $poesessid): array
    {
        $headers = [];

        if (null !== $poesessid) {
            $headers['Cookie'] = sprintf('POESESSID=%s', $poesessid);
        }

        return ['headers' => $headers];
    }
}
