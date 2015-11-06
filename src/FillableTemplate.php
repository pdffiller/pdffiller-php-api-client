<?php

namespace aslikeyou\OAuth2\Client\Provider;

class FillableTemplate extends BaseEntity
{
    /**
     * @param $id
     * @return mixed
     */
    public function dictionary($id) {
        return $this->client->queryApiCall('fillable_template/'.$id);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function download($id) {
        return $this->client->queryApiCall('fillable_template/'.$id.'/download');
    }

    /**
     * @param $id
     * @param array $fields ex: [ 'Text_1' => 'hello world', 'Number_1' => '123' ]
     * @return mixed
     */
    public function makeFillableTemplate($id, array $fields) {
        return $this->client->postApiCall('fillable_template', [
            'json' => [
                'document_id' => $id,
                'fillable_fields' => $fields
            ]
        ]);
    }
}
