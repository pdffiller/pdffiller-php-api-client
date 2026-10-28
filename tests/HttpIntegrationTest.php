<?php

namespace PDFfiller\OAuth2\Client\Provider\Tests;

use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;
use PDFfiller\OAuth2\Client\Provider\Exceptions\ResponseException;
use PDFfiller\OAuth2\Client\Provider\PDFfiller;
use PDFfiller\OAuth2\Client\Provider\Template;
use PDFfiller\OAuth2\Client\Provider\Uploader;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\NetworkExceptionInterface;
use UnexpectedValueException;

/**
 * Sends real HTTP requests through the default Guzzle handler to the PHP built-in
 * web server running tests/Fixtures/api-server.php.
 */
class HttpIntegrationTest extends TestCase
{
    /** @var resource|null */
    private static $server;

    /** @var string */
    private static $baseUrl;

    public static function setUpBeforeClass(): void
    {
        $port = self::freePort();
        self::$baseUrl = "http://127.0.0.1:{$port}";

        // A single process (no PHP_CLI_SERVER_WORKERS): proc_terminate() would not stop the workers
        self::$server = proc_open(
            [PHP_BINARY, '-S', "127.0.0.1:{$port}", __DIR__ . '/Fixtures/api-server.php'],
            [['pipe', 'r'], ['file', '/dev/null', 'w'], ['file', '/dev/null', 'w']],
            $pipes
        );

        for ($i = 0; $i < 100; $i++) {
            $socket = @fsockopen('127.0.0.1', $port);
            if ($socket) {
                fclose($socket);
                return;
            }
            usleep(50000);
        }

        self::fail('The PHP built-in web server did not start');
    }

    public static function tearDownAfterClass(): void
    {
        if (self::$server) {
            proc_terminate(self::$server);
            proc_close(self::$server);
            self::$server = null;
        }
    }

    private static function freePort(): int
    {
        $socket = stream_socket_server('tcp://127.0.0.1:0');
        $port = (int) substr(strrchr(stream_socket_get_name($socket, false), ':'), 1);
        fclose($socket);

        return $port;
    }

    private function provider(?string $baseUrl = null, array $options = []): PDFfiller
    {
        $baseUrl = $baseUrl ?? self::$baseUrl;

        return new PDFfiller($options + [
            'clientId' => 'test-client-id',
            'clientSecret' => 'test-client-secret',
            'urlAccessToken' => "{$baseUrl}/v2/oauth/token",
            'urlApiDomain' => "{$baseUrl}/v2/",
        ]);
    }

    private function providerWithToken(?string $baseUrl = null, array $options = []): PDFfiller
    {
        return $this->provider($baseUrl, $options)->setAccessToken(new AccessToken(['access_token' => 'test-access-token']));
    }

    public function testIssuesTokenAndSendsItWithApiCall(): void
    {
        $provider = $this->provider();

        $this->assertSame('server-token', $provider->getAccessToken()->getToken());

        $echo = $provider->queryApiCall('echo', ['query' => ['page' => 2, 'tags' => ['a b', 'c']]]);

        $this->assertSame('GET', $echo['method']);
        $this->assertSame(['page' => '2', 'tags' => ['a b', 'c']], $echo['query']);
        $this->assertSame('Bearer server-token', $echo['authorization']);
        $this->assertSame(PDFfiller::USER_AGENT . '/' . PDFfiller::VERSION, $echo['user_agent']);
        $this->assertSame(200, $provider->getStatusCode());
    }

    public function testRefusedTokenRequestThrowsIdentityProviderException(): void
    {
        $provider = $this->provider(null, ['clientSecret' => 'wrong-secret']);

        try {
            $provider->getAccessToken();
            $this->fail('No exception for a refused token request');
        } catch (IdentityProviderException $e) {
            $this->assertSame('invalid_client', $e->getMessage());
            $this->assertSame(401, $provider->getStatusCode());
        }
    }

    public function testSendsJsonBody(): void
    {
        $echo = $this->providerWithToken()->putApiCall('echo', ['json' => ['name' => 'Docs', 'id' => 7]]);

        $this->assertSame('PUT', $echo['method']);
        $this->assertSame('application/json', $echo['content_type']);
        $this->assertSame(['name' => 'Docs', 'id' => 7], json_decode($echo['body'], true));
    }

    public function testSendsFormParams(): void
    {
        $echo = $this->providerWithToken()->postApiCall('echo', ['form_params' => ['name' => 'A B', 'tags' => ['x', 'y']]]);

        $this->assertSame('application/x-www-form-urlencoded', $echo['content_type']);
        $this->assertSame(['name' => 'A B', 'tags' => ['x', 'y']], $echo['post']);
    }

    public function testUploadsMultipartFile(): void
    {
        $provider = $this->providerWithToken();
        $file = tempnam(sys_get_temp_dir(), 'pdffiller');
        file_put_contents($file, '%PDF-1.4 test');

        try {
            $uploader = new Uploader($provider, EchoTemplate::class);
            $uploader->type = Uploader::TYPE_MULTIPART;
            $uploader->file = $file;
            $uploader->setAdditionalAttributes(['folder_id' => 42, 'fillable' => true]);

            $echo = $uploader->upload()->toArray();
        } finally {
            unlink($file);
        }

        $this->assertSame('POST', $echo['method']);
        $this->assertStringStartsWith('multipart/form-data; boundary=', $echo['content_type']);
        $this->assertSame(['folder_id' => '42', 'fillable' => '1'], $echo['post']);
        $this->assertSame(['file' => ['name' => basename($file), 'contents' => '%PDF-1.4 test']], $echo['files']);
    }

    public static function errorStatuses(): array
    {
        return [
            '400' => [400],
            '403' => [403],
            '404' => [404],
            '422' => [422],
            '500' => [500],
            '502' => [502],
            '503' => [503],
        ];
    }

    #[DataProvider('errorStatuses')]
    public function testErrorsPayloadThrowsResponseException(int $status): void
    {
        $provider = $this->providerWithToken();

        try {
            $provider->queryApiCall("status/{$status}/errors");
            $this->fail('No exception for an errors payload');
        } catch (ResponseException $e) {
            $this->assertStringContainsString("Status {$status}", $e->getMessage());
            $this->assertSame($status, $provider->getStatusCode());
        }
    }

    #[DataProvider('errorStatuses')]
    public function testJsonErrorWithoutErrorsPayloadIsReturned(int $status): void
    {
        $provider = $this->providerWithToken();

        $this->assertSame(['message' => "Status {$status}"], $provider->queryApiCall("status/{$status}/json"));
        $this->assertSame($status, $provider->getStatusCode());
    }

    public static function errorStatusesExcept500(): array
    {
        return array_diff_key(self::errorStatuses(), ['500' => true]);
    }

    // league/oauth2-client returns a non-JSON body as a string, except for status 500
    #[DataProvider('errorStatusesExcept500')]
    public function testNonJsonErrorIsReturnedAsString(int $status): void
    {
        $provider = $this->providerWithToken();

        $this->assertSame("<html>Status {$status}</html>", $provider->queryApiCall("status/{$status}/html"));
        $this->assertSame($status, $provider->getStatusCode());
    }

    public function testNonJson500ThrowsUnexpectedValueException(): void
    {
        $this->expectException(UnexpectedValueException::class);

        $this->providerWithToken()->queryApiCall('status/500/html');
    }

    public function testConnectionErrorThrowsNetworkException(): void
    {
        $provider = $this->providerWithToken('http://127.0.0.1:' . self::freePort());

        $this->expectException(NetworkExceptionInterface::class);

        $provider->queryApiCall('echo');
    }

    public function testTimeoutThrowsNetworkException(): void
    {
        $provider = $this->providerWithToken(null, ['timeout' => 0.5]);
        $started = microtime(true);

        try {
            $provider->queryApiCall('slow');
            $this->fail('No exception for a timed out request');
        } catch (NetworkExceptionInterface $e) {
            $this->assertLessThan(1.5, microtime(true) - $started);
        }
    }
}

/**
 * Uploads to the echo endpoint, so the test can see what the server received.
 */
class EchoTemplate extends Template
{
    protected static $entityUri = 'echo';
}
