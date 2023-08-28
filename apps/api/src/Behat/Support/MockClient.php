<?php declare(strict_types=1);

namespace DumpIt\Api\Behat\Support;

use DumpIt\StashFilter\Infrastructure\HttpClient\PoeWebsiteHttpClient;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class MockClient extends MockHttpClient
{
    private const CHANGE_SPRINF_SUB_MATCH_MULTIPLE_CHAR_REGEX = '/\%\w/';
    private const ESCAPE_FORWARD_SLASH_REGEX = '/\//';

    private KernelInterface $kernel;

    public function __construct(KernelInterface $kernel) {
        parent::__construct($this->generateResponse(...));

        $this->kernel = $kernel;
    }

    private function generateResponse(string $method, string $url, array $options = []): ResponseInterface
    {
        $url = preg_replace('/\%\w/', '.+', $url);

        if ('GET' !== $method) {
            return new MockResponse('{}');
        }

        if ($this->doUrlsMatch(PoeWebsiteHttpClient::TABS_URL, $url)) {
            return new MockResponse($this->getFileContent('tabs'));
        }

        if ($this->doUrlsMatch(PoeWebsiteHttpClient::TAB_ITEMS_URL, $url)) {
            return new MockResponse($this->getFileContent('tabItems'));
        }

        if ($this->doUrlsMatch(PoeWebsiteHttpClient::MODS_URL, $url)) {
            return new MockResponse($this->getFileContent('mods'));
        }

        if ($this->doUrlsMatch(PoeWebsiteHttpClient::LEAGUE_URL, $url)) {
            return new MockResponse($this->getFileContent('leagues'));
        }

        return new MockResponse('{}');
    }

    private function doUrlsMatch(string $baseUrl, string $actualUrl): bool
    {
        $urlRegex = sprintf(
            '/%s/',
            preg_replace(
                [self::CHANGE_SPRINF_SUB_MATCH_MULTIPLE_CHAR_REGEX, self::ESCAPE_FORWARD_SLASH_REGEX],
                ['.+', '\\/'],
                preg_quote($baseUrl)
            )
        );

        return (bool) preg_match($urlRegex, $actualUrl);
    }

    private function getFileContent(string $fileName): string
    {
        return file_get_contents(
            sprintf(
                '%s/src/Behat/Support/PoeApiResponses/%s.json',
                $this->kernel->getProjectDir(),
                $fileName,
            )
        );
    }
}
