<?php

namespace PDFfiller\OAuth2\Client\Provider;


class Token extends BaseEntity
{
    /**
     * @return mixed
     */
    public function listItems() {
        return $this->client->queryApiCall('token/');
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data = []) {
        return $this->client->postApiCall('token/',  [
            'json' => [
                'data' => $data
            ]
        ]);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function info($id) {
        return $this->client->queryApiCall('token/'.$id);
    }

    /**
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function update($id, array $data = []) {
        return $this->client->putApiCall('token/'.$id, [
            'json' => [
                'data' => $data
            ]
        ]);
    }
}
