<?php declare(strict_types=1);

namespace DumpIt\StashFilter\Infrastructure\HttpClient;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class PoeWebsiteHttpClient
{
    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function isTokenValid(string $username, string $token): string|null
    {
        $response = $this->client->request(
            'GET',
            'https://www.pathofexile.com/my-account',
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
        $leagues = $this->client->request('GET', 'https://www.pathofexile.com/api/leagues')->toArray();

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
        $mods = $this->client->request('GET', 'https://www.pathofexile.com/api/trade/data/stats')->toArray();

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
        $tabs = $this->client->request(
            'GET',
            sprintf(
                'https://www.pathofexile.com/character-window/get-stash-items?accountName=%s&realm=%s&league=%s&tabs=1&tabIndex=0',
                $username,
                $realm,
                $leagueId,
            ),
            $this->headers($poesessid),
        )->toArray();

        return array_map(
            function (array $tab) {
                return [
                    'id' => $tab['id'],
                    'text' => $tab['text'],
                ];
            },
            $tabs['tabs'],
        ); 
    }

    public function getTabItems(string $poesessid, string $username, string $realm, string $leagueId, int $tabIndex): array
    {
        $tab = $this->client->request(
            'GET',
            sprintf(
                'https://www.pathofexile.com/character-window/get-stash-items?accountName=%s&realm=%s&league=%s&tabs=1&tabIndex=%d',
                $username,
                $realm,
                $leagueId,
                $tabIndex
            ),
            $this->headers($poesessid),
        )->toArray();

        $items = array_filter(
            $tab['items'],
            function (array $item) {
                return $item['identified'];
            },
        );

        return array_map(
            function (array $item) {
                if ($item['identified']) {
                    return [
                        'id' => $item['id'],
                        'name' => $item['name'],
                        'mods' => $item['explicitMods'],
                        'ilvl' => $item['ilvl'],
                        'baseType' => $item['baseType'],
                    ];
                }
            },
            $items,
        );
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
