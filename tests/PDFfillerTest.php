<?php

namespace PDFfiller\OAuth2\Client\Provider\Tests;

use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;
use PDFfiller\OAuth2\Client\Provider\Enums\GrantType;
use PDFfiller\OAuth2\Client\Provider\Exceptions\InvalidBodySourceException;
use PDFfiller\OAuth2\Client\Provider\Exceptions\InvalidRequestException;
use PDFfiller\OAuth2\Client\Provider\Exceptions\OptionsMissingException;
use PDFfiller\OAuth2\Client\Provider\Exceptions\ResponseException;
use PDFfiller\OAuth2\Client\Provider\Exceptions\TokenMissingException;
use PDFfiller\OAuth2\Client\Provider\PDFfiller;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use UnexpectedValueException;

class PDFfillerTest extends TestCase
{
    use MockedProviderTrait;

    public function testRequiresApiDomainOption(): void
    {
        $this->expectException(OptionsMissingException::class);

        new PDFfiller(['clientId' => 'id', 'clientSecret' => 'secret', 'urlAccessToken' => 'https://api.test/token']);
    }

    public function testPasswordGrantIssuesAccessToken(): void
    {
        $provider = $this->provider([
            self::json(['access_token' => 'tok-1', 'token_type' => 'Bearer', 'expires_in' => 3600, 'refresh_token' => 'ref-1']),
        ]);

        $token = $provider->getAccessToken(new GrantType(GrantType::PASSWORD_GRANT), [
            'username' => 'user@example.test',
            'password' => 'test-password',
        ]);

        $this->assertInstanceOf(AccessToken::class, $token);
        $this->assertSame('tok-1', $token->getToken());
        $this->assertSame('ref-1', $token->getRefreshToken());

        $request = $this->request();
        $this->assertSame('POST', $request->getMethod());
        $this->assertSame('https://api.test/v2/oauth/token', (string) $request->getUri());
        $this->assertSame('', $request->getHeaderLine('Authorization'));
        $body = $this->formBody($request);
        $this->assertSame('password', $body['grant_type']);
        $this->assertSame('user@example.test', $body['username']);
        $this->assertSame('test-password', $body['password']);
        $this->assertSame('test-client-id', $body['client_id']);
        $this->assertSame('test-client-secret', $body['client_secret']);
    }

    public function testDefaultGrantIsClientCredentials(): void
    {
        $provider = $this->provider([self::json(['access_token' => 'tok-2'])]);

        $this->assertSame('tok-2', $provider->getAccessToken()->getToken());
        $this->assertSame('client_credentials', $this->formBody($this->request())['grant_type']);
    }

    public function testAccessTokenIsReusedAfterFirstRequest(): void
    {
        $provider = $this->provider([self::json(['access_token' => 'tok-1'])]);

        $first = $provider->getAccessToken();
        $second = $provider->getAccessToken();

        $this->assertSame($first, $second);
        $this->assertCount(1, $this->history);
    }

    public function testOAuthErrorResponseIsRefused(): void
    {
        $provider = $this->provider([self::json(['error' => 'invalid_client', 'message' => 'Client authentication failed'], 401)]);

        try {
            $provider->getAccessToken();
            $this->fail('No exception for a refused token request');
        } catch (IdentityProviderException $e) {
            $this->assertSame(401, $provider->getStatusCode());
        }

        // A failed request must not leave a token behind
        $this->expectException(TokenMissingException::class);
        $provider->queryApiCall('folders');
    }

    public function testErrorsPayloadOnTokenRequestIsRefused(): void
    {
        $provider = $this->provider([self::json(['errors' => [['message' => 'Invalid credentials']]], 400)]);

        $this->expectException(ResponseException::class);

        $provider->getAccessToken(new GrantType(GrantType::PASSWORD_GRANT), ['username' => 'u', 'password' => 'p']);
    }

    public function testApiCallWithoutTokenIsRefusedAndNotSent(): void
    {
        $provider = $this->provider([self::json([])]);

        try {
            $provider->queryApiCall('folders');
            $this->fail('No exception without access token');
        } catch (TokenMissingException $e) {
            $this->assertCount(0, $this->history);
        }
    }

    public function testApiCallSendsBearerTokenAndResolvesUrlAgainstApiDomain(): void
    {
        $provider = $this->provider([self::json(['items' => []])], true);

        $this->assertSame(['items' => []], $provider->queryApiCall('folders', ['query' => ['per_page' => 5]]));

        $request = $this->request();
        $this->assertSame('GET', $request->getMethod());
        $this->assertSame('https://api.test/v2/folders?per_page=5', (string) $request->getUri());
        $this->assertSame('Bearer test-access-token', $request->getHeaderLine('Authorization'));
        $this->assertSame(PDFfiller::USER_AGENT . '/' . PDFfiller::VERSION, $request->getHeaderLine('User-Agent'));
        $this->assertSame(200, $provider->getStatusCode());
    }

    public function testFormParamsAreSentUrlEncoded(): void
    {
        $provider = $this->provider([self::json(['id' => 1], 201)], true);

        $provider->postApiCall('folders', ['form_params' => ['name' => 'A B', 'tags' => ['x', 'y']]]);

        $request = $this->request();
        $this->assertSame('application/x-www-form-urlencoded', $request->getHeaderLine('Content-Type'));
        $this->assertSame(['name' => 'A B', 'tags' => ['x', 'y']], $this->formBody($request));
        $this->assertSame(201, $provider->getStatusCode());
    }

    public function testJsonOptionIsSentAsJson(): void
    {
        $provider = $this->provider([self::json(['id' => 1])], true);

        $provider->putApiCall('folders/1', ['json' => ['name' => 'Docs']]);

        $request = $this->request();
        $this->assertSame('PUT', $request->getMethod());
        $this->assertSame('application/json', $request->getHeaderLine('Content-Type'));
        $this->assertSame(['name' => 'Docs'], json_decode((string) $request->getBody(), true));
    }

    public function testFormParamsAndMultipartTogetherAreRefused(): void
    {
        $provider = $this->provider([], true);

        $this->expectException(InvalidBodySourceException::class);

        $provider->postApiCall('document', ['form_params' => ['a' => 1], 'multipart' => [['name' => 'file', 'contents' => 'x']]]);
    }

    public function testErrorsPayloadOnApiCallThrowsResponseException(): void
    {
        $provider = $this->provider([self::json(['errors' => [['message' => 'Folder not found']]], 404)], true);

        try {
            $provider->deleteApiCall('folders/404');
            $this->fail('No exception for an errors payload');
        } catch (ResponseException $e) {
            $this->assertStringContainsString('Folder not found', $e->getMessage());
            $this->assertSame(404, $provider->getStatusCode());
        }
    }

    public static function urlsOutsideApiDomain(): array
    {
        return [
            'other host' => ['https://evil.test/steal'],
            'protocol-relative host' => ['//evil.test/steal'],
            'scheme downgrade' => ['http://api.test/v2/folders'],
            'other port' => ['https://api.test:8443/v2/folders'],
            'host suffix' => ['https://api.test.evil.test/v2/folders'],
        ];
    }

    #[DataProvider('urlsOutsideApiDomain')]
    public function testAccessTokenIsNotSentOutsideApiDomain(string $url): void
    {
        $provider = $this->provider([self::json([])], true);

        try {
            $provider->queryApiCall($url);
            $this->fail('Request outside urlApiDomain was not refused');
        } catch (InvalidRequestException $e) {
            $this->assertCount(0, $this->history);
        }
    }

    public function testAbsoluteUrlOnApiDomainIsAllowed(): void
    {
        $provider = $this->provider([self::json(['items' => []])], true);

        $provider->queryApiCall('https://API.test/v2/folders?page=2');

        $this->assertSame('https://api.test/v2/folders?page=2', (string) $this->request()->getUri());
        $this->assertSame('Bearer test-access-token', $this->request()->getHeaderLine('Authorization'));
    }

    public function testTokenResponseWithoutAccessTokenIsRefused(): void
    {
        $provider = $this->provider([self::json(['message' => 'Internal error'], 500)]);

        try {
            $provider->getAccessToken();
            $this->fail('A token response without access_token was accepted');
        } catch (\InvalidArgumentException $e) {
            $this->expectException(TokenMissingException::class);
            $provider->queryApiCall('folders');
        }
    }

    public function testNonJsonTokenResponseIsRefused(): void
    {
        $provider = $this->provider([new \GuzzleHttp\Psr7\Response(502, ['Content-Type' => 'text/html'], '<html>Bad Gateway</html>')]);

        $this->expectException(UnexpectedValueException::class);

        $provider->getAccessToken();
    }
}
