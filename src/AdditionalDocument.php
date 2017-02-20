<?php

namespace PDFfiller\OAuth2\Client\Provider;

use PDFfiller\OAuth2\Client\Provider\Core\Model;
use PDFfiller\OAuth2\Client\Provider\Exceptions\MethodNotSupportedException;

/**
 * Class AdditionalDocument
 * @package PDFfiller\OAuth2\Client\DTO
 *
 * @property string $name
 * @property string $filename
 * @property string $ip
 * @property int $date_created
 */
abstract class AdditionalDocument extends Model
{
    const DOWNLOAD = 'download';

    /**
     * FillRequestId or SignatureRequestId
     * @var int|null
     */
    protected $requestId = null;

    /**
     * Recipient for SignatureRequest and FilledForm for FillRequest
     * @var int|null
     */
    protected $resourceId = null;

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            'name',
            'filename',
            'id',
            'ip',
            'date_created',
        ];
    }

    /**
     * AdditionalDocument constructor.
     * @param PDFfiller $provider
     * @param int $requestId
     * @param int $resourceId
     * @param array $properties
     */
    public function __construct(PDFfiller $provider, $requestId, $resourceId, $properties = [])
    {
        if (is_string($properties)) {
            $properties = [
                'name' => $properties
            ];
        }

        $this->requestId = $requestId;
        $this->resourceId = $resourceId;
        parent::__construct($provider, $properties);
    }

    /**
     * Downloads additional document
     * @param array $parameters
     * @return mixed
     */
    public function download($parameters = [])
    {
        return self::query(
            $this->client,
            [
                $this->requestId,
                $this->getResourceIdentifier(),
                $this->resourceId,
                self::DOWNLOAD
            ],
            $parameters
        );
    }

    /**
     * @inheritdoc
     */
    public static function one(PDFfiller $provider, $id)
    {
        throw new MethodNotSupportedException("Can't get document, see FillRequestForm and SignatureRequestRecipient classes");
    }

    /**
     * @inheritdoc
     */
    public static function all(PDFfiller $provider, array $queryParams = [])
    {
        throw new MethodNotSupportedException("Can't get documents, see FillRequestForm and SignatureRequestRecipient classes");
    }

    /**
     * Returns the resource identifier
     * @return string
     */
    protected abstract function getResourceIdentifier();
}
