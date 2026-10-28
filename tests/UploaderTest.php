<?php

namespace PDFfiller\OAuth2\Client\Provider\Tests;

use PDFfiller\OAuth2\Client\Provider\Template;
use PDFfiller\OAuth2\Client\Provider\Uploader;
use PHPUnit\Framework\TestCase;

class UploaderTest extends TestCase
{
    use MockedProviderTrait;

    public function testMultipartUploadSendsFileAndScalarAttributes(): void
    {
        $provider = $this->provider([self::json(['id' => 11, 'name' => 'form.pdf'])], true);
        $file = tempnam(sys_get_temp_dir(), 'pdffiller');
        file_put_contents($file, '%PDF-1.4 test');

        try {
            $uploader = new Uploader($provider, Template::class);
            $uploader->type = Uploader::TYPE_MULTIPART;
            $uploader->file = $file;
            $uploader->setAdditionalAttributes(['folder_id' => 42, 'ratio' => 1.5, 'fillable' => true, 'name' => 'form.pdf']);

            $template = $uploader->upload();
        } finally {
            unlink($file);
        }

        $this->assertInstanceOf(Template::class, $template);
        $this->assertSame(11, $template->id);

        $request = $this->request();
        $this->assertSame('POST', $request->getMethod());
        $this->assertStringStartsWith('multipart/form-data; boundary=', $request->getHeaderLine('Content-Type'));
        $body = (string) $request->getBody();
        $this->assertStringContainsString("name=\"file\"; filename=\"" . basename($file) . "\"", $body);
        $this->assertStringContainsString('%PDF-1.4 test', $body);
        $this->assertMultipartField('folder_id', '42', $body);
        $this->assertMultipartField('ratio', '1.5', $body);
        $this->assertMultipartField('fillable', '1', $body);
        $this->assertMultipartField('name', 'form.pdf', $body);
    }

    public function testUrlUploadSendsJson(): void
    {
        $provider = $this->provider([self::json(['id' => 12])], true);

        $uploader = new Uploader($provider, Template::class);
        $uploader->type = Uploader::TYPE_URL;
        $uploader->file = 'https://files.test/form.pdf';
        $uploader->setAdditionalAttributes(['folder_id' => 42]);
        $uploader->upload();

        $this->assertSame('application/json', $this->request()->getHeaderLine('Content-Type'));
        $this->assertSame(['folder_id' => 42, 'file' => 'https://files.test/form.pdf'], json_decode((string) $this->request()->getBody(), true));
    }

    // guzzlehttp/psr7 1.x/2.x add a Content-Length header to each part, 3.x does not
    private function assertMultipartField(string $name, string $value, string $body): void
    {
        $this->assertMatchesRegularExpression(
            '/name="' . preg_quote($name, '/') . '"\r\n(Content-Length: \d+\r\n)?\r\n' . preg_quote($value, '/') . '\r\n/',
            $body
        );
    }
}
