<?php

namespace PDFfiller\OAuth2\Client\Provider\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use League\OAuth2\Client\Token\AccessToken;
use PDFfiller\OAuth2\Client\Provider\PDFfiller;
use Psr\Http\Message\RequestInterface;

trait MockedProviderTrait
{
    /** @var array */
    private $history = [];

    private function provider(array $responses, bool $withToken = false): PDFfiller
    {
        $this->history = [];
        $stack = HandlerStack::create(new MockHandler($responses));
        $stack->push(Middleware::history($this->history));

        $provider = new PDFfiller([
            'clientId' => 'test-client-id',
            'clientSecret' => 'test-client-secret',
            'urlAccessToken' => 'https://api.test/v2/oauth/token',
            'urlApiDomain' => 'https://api.test/v2/',
        ], ['httpClient' => new Client(['handler' => $stack])]);

        if ($withToken) {
            $provider->setAccessToken(new AccessToken(['access_token' => 'test-access-token']));
        }

        return $provider;
    }

    private static function json($data, int $status = 200): Response
    {
        return new Response($status, ['Content-Type' => 'application/json'], json_encode($data));
    }

    private function request(int $index = -1): RequestInterface
    {
        $index = $index < 0 ? count($this->history) + $index : $index;

        return $this->history[$index]['request'];
    }

    private function formBody(RequestInterface $request): array
    {
        parse_str((string) $request->getBody(), $body);

        return $body;
    }
}
