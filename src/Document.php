<?php

namespace PDFfiller\OAuth2\Client\Provider;


class Document extends BaseEntity
{
    /**
     * @param $url
     * @return mixed
     */
    public function uploadViaUrl($url) {
        return $this->client->postApiCall('document', [
            'json' => [
                'file' => $url
            ]
        ]);
    }

    public function uploadViaMultipart($fopenResource) {
        return $this->client->postApiCall('document', [
            'multipart' => [
                [
                    'name' => 'file',
                    'contents' => $fopenResource
                ]
            ]
        ]);
    }

    public function itemsList($page = 1) {
        return $this->client->queryApiCall('document/?page='.$page);
    }

    public function itemInfo($id) {
        return $this->client->queryApiCall('document/'.$id);
    }
}