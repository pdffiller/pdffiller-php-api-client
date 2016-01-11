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

    /**
     * @param int $id
     * @param array $params example:
     *      ["access" => "full", "status" => "public", "email_required" => true,
     *       "name_required" => true, "custom_message" => "string", 
     *       "notification_emails" => [ [ "name" => "string", "email" => "email" ] ] ]
     * @return mixed
     */
    public function create($id, array $params = []) {
        $params = array_merge($params, ['document_id' => $id]);
        return $this->client->postApiCall('fill_request/',  [
            'json' => $params,
        ]);
    }

    /**
     * @param int $id
     * @param array $params example:
     *      ["access" => "full", "status" => "public", "email_required" => true,
     *       "name_required" => true, "custom_message" => "string",
     *       "notification_emails" => [ [ "name" => "string", "email" => "email" ] ] ]
     * @return mixed
     */
    public function update($id, array $params = []) {
        $params = array_merge($params, ['document_id' => $id]);
        return $this->client->putApiCall('fill_request/'.$id, [
            'json' => $params,
        ]);
    }

    public function delete($id) {
        return $this->client->deleteApiCall('fill_request/'.$id);
    }

}
