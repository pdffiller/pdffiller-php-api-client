<?php

namespace aslikeyou\OAuth2\Client\Provider;

class SignatureRequestRecipient extends BaseEntity
{
    /**
     * @var int
     */
    private $signatureRequestId;

    /**
     * @param Pdffiller $client
     * @param int $signatureRequestId
     */
    public function __construct(Pdffiller $client, $signatureRequestId) {
        parent::__construct($client);

        $this->signatureRequestId = $signatureRequestId;
    }

    private function recipient_path($id = '') {
        return "signature_request/$this->signatureRequestId/recipient/$id";
    }

    public function info($id) {
        return $this->client->queryApiCall($this->recipient_path($id));
    }

    /**
     * @param array $params example:
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
     * @return mixed
     */
    public function create(array $params) {
        return $this->client->postApiCall($this->recipient_path(),  [
            'json' => $params,
        ]);
    }

    public function remind($id) {
        return $this->client->putApiCall($this->recipient_path($id).'/remind');
    }
}
