<?php

namespace PDFfiller\OAuth2\Client\Provider\Alt;


abstract class Rules
{
    private static $rules = [
        'fillableTemplate' => [
            'document_id' => 'required',
            'fillable_fields' => 'required'
        ],
        'callback' => [
            'document_id' => 'required|integer',
            'event_id' => 'required|in:fill_request.done,signature_request.done',
            'callback_url' => 'required|url',
        ],
        'documentMultipart' => [
            'file' => 'between:1,25000|mimes:pdf,doc,docx,ppt,pptx', // max 25 megabytes
        ],
        'documentUrl' => [
            'file' => 'url',
        ],
        'filledForm' => [
            'status' => 'accepted',
        ],
        'fillRequest' => [
            'document_id'                 => 'required|integer|min:1',
            'access'                      => 'in:full,signature',
            'status'                      => 'in:public,private',
            'email_required'              => 'boolean',
            'name_required'               => 'boolean',
            'custom_message'              => 'string',
            'notification_emails'         => 'array',
            'callback_url'                => 'url',
            'redirect_url'                => 'url',
            'allow_downloads'             => 'boolean',
            'notification_emails.*.name'  => 'string',
            'notification_emails.*.email' => 'required|email',
        ],
        'folder' => [
            'name' => 'required|string'
        ],
        'signRequest' => [
            'callback_url'                      => 'url',
            'document_id'                       => 'required|numeric',
            'method'                            => 'required|in:sendtoeach,sendtogroup',
            'sign_in_order'                     => 'required_if:method,sendtogroup|boolean',
            'envelope_name'                     => 'required_if:method,sendtogroup|max:255',
            'recipients'                        => 'required|array|between:1,20',
            'security_pin'                      => 'required_if:method,sendtoeach|in:standard,enhanced',
            'pin'                               => 'required_if:security_pin,enhanced|max:6',
            'recipients.*.email'                => 'required|email',
            'recipients.*.name'                 => 'required',
            'recipients.*.require_photo'        => 'boolean',
            'recipients.*.message_subject'      => 'required',
            'recipients.*.message_text'         => 'required',
            'recipients.*.access'               => 'required',
            'recipients.*.order'                => 'required_if:sign_in_order,1,true|numeric',
            'recipients.*.additional_documents' => 'array|max:150',
        ],
        'token' => [
            'data' => 'required|array'
        ],
    ];

    public static function rules($endpoint = null)
    {
        $rules = self::$rules;

        if ($endpoint && isset($rules[$endpoint])) {
            return $rules[$endpoint];
        }

        return $rules;
    }
}