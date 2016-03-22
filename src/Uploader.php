<?php

namespace PDFfiller\OAuth2\Client\Provider;


use PDFfiller\OAuth2\Client\Provider\Core\Model;

class Uploader extends Model
{
    public static $entityUri = 'document';


    public function attributes()
    {
        return [];
    }

    public function uploadViaUrl($url) {
        return $this->upload([
            'json' => [
                'file' => $url
            ]
        ]);
    }

    public function uploadViaMultipart($fopenResource) {
        return $this->upload([
            'multipart' => [
                [
                    'name' => 'file',
                    'contents' => $fopenResource
                ]
            ]
        ]);
    }

    protected function upload($params)
    {
        $uri = static::getUri();
        $document = static::post($this->client, $uri, $params);
        $instance = new Document($this->client, $document);
        $instance->cacheFields($document);

        return $instance;
    }
}