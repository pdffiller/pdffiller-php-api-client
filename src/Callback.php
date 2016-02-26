<?php

namespace PDFfiller\OAuth2\Client\Provider;


class Callback extends BaseEntity
{
    /**
     * @return mixed
     */
    public function listItems() {
        return $this->client->queryApiCall('callback/');
    }

    /**
     * @param int $document_id
     * @param array $params example:
     *      ["event_id" => "fill_request.done", "callback_url" => "http://pdffiller.com/callback_destination" ]
     * @return mixed
     */
    public function create($document_id, array $params = []) {
        $params = array_merge($params, ['document_id' => $document_id]);
        return $this->client->postApiCall('callback/',  [
            'json' => $params,
        ]);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function info($id) {
        return $this->client->queryApiCall('callback/'.$id);
    }

    /**
     * @param int $id
     * @param array $params example:
     *      ["event_id" => "fill_request.done", "callback_url" => "http://pdffiller.com/callback_destination" ]
     * @return mixed
     */
    public function update($id, array $params = []) {
        return $this->client->putApiCall('callback/'.$id, [
            'json' => $params,
        ]);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function delete($id) {
        return $this->client->deleteApiCall('callback/'.$id);
    }
}
