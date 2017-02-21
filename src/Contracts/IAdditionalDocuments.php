<?php

namespace PDFfiller\OAuth2\Client\Provider\Contracts;

use PDFfiller\OAuth2\Client\Provider\AdditionalDocument;

/**
 * Interface IAdditionalDocuments
 * @package PDFfiller\OAuth2\Client\Provider\Contracts
 */
interface IAdditionalDocuments
{
    const ADDITIONAL_DOCUMENTS = 'additional_document';
    const ADDITIONAL_DOCUMENTS_ALL = 'all';
    const ADDITIONAL_DOCUMENTS_DOWNLOAD = 'download';

    /**
     * Returns the list of additional documents
     * @param array $parameters
     * @return AdditionalDocument
     */
    public function additionalDocuments($parameters = []);

    /**
     * Returns the additional document
     * @param $documentId
     * @param array $parameters
     * @return AdditionalDocument
     */
    public function additionalDocument($documentId, $parameters = []);

    /**
     * Downloads all additional documents
     * @param array $parameters
     * @return mixed
     */
    public function downloadAdditionalDocuments($parameters = []);

    /**
     * Creates an instance of additional document
     * @param array $parameters
     * @return AdditionalDocument
     */
    public function createAdditionalDocument($parameters = []);
}
