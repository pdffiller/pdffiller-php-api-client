<?php

namespace aslikeyou\OAuth2\Client\Provider;

class FillRequestForm extends BaseEntity
{
    /**
     * @var int
     */
    private $fillRequestId;

    /**
     * @param Pdffiller $client
     * @param int $signatureRequestId
     */
    public function __construct(Pdffiller $client, $signatureRequestId) {
        parent::__construct($client);

        $this->fillRequestId = $signatureRequestId;
    }

    private function filled_form_path($id = '') {
        return "fill_request/$this->fillRequestId/filled_form/$id";
    }

    public function listItems() {
        return $this->client->queryApiCall($this->filled_form_path());
    }

    public function info($id) {
        return $this->client->queryApiCall($this->filled_form_path($id));
    }

    public function delete($id) {
        return $this->client->deleteApiCall($this->filled_form_path($id));
    }

    public function export($id) {
        return $this->client->queryApiCall($this->filled_form_path($id).'/export');
    }

    public function download($id) {
        return $this->client->queryApiCall($this->filled_form_path($id).'/download');
    }
}
