<?php

namespace PDFfiller\OAuth2\Client\Provider\Tests;

use PDFfiller\OAuth2\Client\Provider\Core\ModelsList;
use PDFfiller\OAuth2\Client\Provider\Folder;
use PHPUnit\Framework\TestCase;

class ModelTest extends TestCase
{
    use MockedProviderTrait;

    public function testAllReturnsModelsList(): void
    {
        $provider = $this->provider([self::json([
            'items' => [['id' => 7, 'name' => 'Docs'], ['id' => 8, 'name' => 'Old']],
            'total' => 2, 'current_page' => 1, 'per_page' => 15, 'prev_page_url' => null, 'next_page_url' => null,
        ])], true);

        $list = Folder::all($provider);

        $this->assertInstanceOf(ModelsList::class, $list);
        $this->assertSame(2, $list->getTotal());
        $this->assertSame(['Docs', 'Old'], array_values(array_map(fn (Folder $folder) => $folder->name, iterator_to_array($list))));
        $this->assertSame('https://api.test/v2/folders/', (string) $this->request()->getUri());
    }

    public function testOneLoadsModelById(): void
    {
        $provider = $this->provider([self::json(['id' => 7, 'name' => 'Docs'])], true);

        $folder = Folder::one($provider, 7);

        $this->assertSame(7, $folder->id);
        $this->assertSame('Docs', $folder->name);
        $this->assertSame(['id' => 7, 'name' => 'Docs'], $folder->toArray());
        $this->assertSame('https://api.test/v2/folders/7', (string) $this->request()->getUri());
    }

    public function testSaveCreatesNewModel(): void
    {
        $provider = $this->provider([self::json(['id' => 9, 'name' => 'New'])], true);

        $folder = new Folder($provider, ['name' => 'New']);
        $folder->save();

        $this->assertSame(9, $folder->id);
        $request = $this->request();
        $this->assertSame('POST', $request->getMethod());
        $this->assertSame(['name' => 'New'], json_decode((string) $request->getBody(), true));
    }

    public function testSaveUpdatesExistingModel(): void
    {
        $provider = $this->provider([
            self::json(['folder_id' => 7, 'name' => 'Docs']),
            self::json(['folder_id' => 7, 'name' => 'Renamed']),
        ], true);

        $folder = Folder::one($provider, 7);
        $folder->name = 'Renamed';
        $folder->save();

        $request = $this->request();
        $this->assertSame('PUT', $request->getMethod());
        $this->assertSame('https://api.test/v2/folders/7', (string) $request->getUri());
        $this->assertSame('Renamed', json_decode((string) $request->getBody(), true)['name']);
        $this->assertSame('Renamed', $folder->name);
    }

    public function testDeleteOne(): void
    {
        $provider = $this->provider([self::json(['total' => 1])], true);

        $this->assertSame(['total' => 1], Folder::deleteOne($provider, 9));
        $this->assertSame('DELETE', $this->request()->getMethod());
        $this->assertSame('https://api.test/v2/folders/9', (string) $this->request()->getUri());
    }
}
