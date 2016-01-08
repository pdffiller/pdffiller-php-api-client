<?php

namespace aslikeyou\OAuth2\Client\Provider;

class SignatureRequest extends BaseEntity
{
    public function listItems() {
        return $this->client->queryApiCall('signature_request/');
    }

    public function info($id) {
        return $this->client->queryApiCall('signature_request/'.$id);
    }

    /**
     * @param int|string $id
     * @param array $params example:
     *      [
     *          "method" => "sendtoeach",
     *          "envelope_name" => "string",
     *          "security_pin" => "standard",
     *          "sign_in_order" => true,
     *          "recipients" => [
     *              [
     *                  "email" => "string",
     *                  "name" => "string",
     *                  "order" => 0,
     *                  "message_subject" => "string",
     *                  "message_text" => "string",
     *                  "date_created" => 0,
     *                  "date_signed" => 0,
     *                  "access" => "full",
     *                  "additional_documents" => [
     *                      [
     *                          "document_request_notification" => "string"
     *                      ]
     *                  ],
     *                  "require_photo" => true
     *              ]
     *          ]
     *      ]
     * @return mixed
     */
    public function create($id, array $params) {
        $params = array_merge($params, ['document_id' => $id]);
        return $this->client->postApiCall('signature_request/',  [
            'json' => $params,
        ]);
    }

    public function delete($id) {
        return $this->client->deleteApiCall('signature_request/'.$id);
    }

    public function certificate($id) {
        return $this->client->queryApiCall('signature_request/'.$id.'/certificate');
    }

    public function signedDocument($id) {
        return $this->client->queryApiCall('signature_request/'.$id.'/signed_document');
    }
}
