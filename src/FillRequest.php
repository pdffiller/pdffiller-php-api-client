<?php

namespace aslikeyou\OAuth2\Client\Provider;


class FillRequest extends BaseEntity
{
    public function listItems() {
        return $this->client->queryApiCall('fill_request/');
    }

    public function info($id) {
        return $this->client->queryApiCall('fill_request/'.$id);
    }
}