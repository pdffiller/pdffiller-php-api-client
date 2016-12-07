<?php

namespace PDFfiller\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;
use PDFfiller\OAuth2\Client\Provider\Exceptions\InvalidBodyException;
use PDFfiller\OAuth2\Client\Provider\Exceptions\InvalidBodySourceException;
use PDFfiller\OAuth2\Client\Provider\Exceptions\InvalidQueryException;
use PDFfiller\OAuth2\Client\Provider\Exceptions\OptionsMissingException;
use PDFfiller\OAuth2\Client\Provider\Exceptions\ResponseException;
use PDFfiller\OAuth2\Client\Provider\Exceptions\TokenMissingException;
use League\OAuth2\Client\Provider\GenericProvider;
use Psr\Http\Message\RequestInterface;
use \GuzzleHttp\Psr7 as Psr7;
use Psr\Http\Message\ResponseInterface;

/**
 * Represents a generic service provider that may be used to interact with any
 * OAuth 2.0 service provider, using Bearer token authentication.
 */
class PDFfiller extends GenericProvider
{
    /** @var   */
    private $urlApiDomain;
    private $accessToken;
    private $statusCode;

    /**
     * PDFfiller constructor.
     * @param array $options
     * @param array $collaborators
     */
    public function __construct(array $options = [], array $collaborators = [])
    {
        $this->assertPdffillerOptions($options);

        $possible   = $this->getPdffillerOptions();
        $configured = array_intersect_key($options, array_flip($possible));

        foreach ($configured as $key => $value) {
            $this->$key = $value;
        }
        // Remove all options that are only used locally
        $options = array_diff_key($options, $configured);

        $options = array_merge([
            'redirectUri'             => 'http://localhost/redirect_uri',
            'urlAuthorize'            => 'http://localhost/url_authorize',
            'urlResourceOwnerDetails' => 'http://localhost/url_resource_owner_details'], $options);

        parent::__construct($options, $collaborators);
    }

    /**
     * Returns request with authentication credentials
     * @param string $method
     * @param string $url
     * @param AccessToken|string $token
     * @param array $options
     * @return RequestInterface
     */
    public function getAuthenticatedRequest($method, $url, $token, array $options = [])
    {
        $baseUri = new Psr7\Uri($this->urlApiDomain);
        $relativeUri = new Psr7\Uri($url);
        $newUri = Psr7\Uri::resolve($baseUri, $relativeUri);

        return parent::getAuthenticatedRequest($method, $newUri, $token, $options);
    }

    /**
     * Applies the array of request options to a request.
     * @param RequestInterface $request
     * @param array $options
     * @return RequestInterface
     * @throws InvalidBodySourceException
     * @throws InvalidBodyException
     * @throws InvalidQueryException
     */
    private function applyOptions(RequestInterface $request, array &$options)
    {
        $modify = [];

        if (isset($options['form_params'])) {
            if (isset($options['multipart'])) {
                throw new InvalidBodySourceException();
            }
            $options['body'] = http_build_query($options['form_params'], null, '&');
            unset($options['form_params']);
            $options['_conditional']['Content-Type'] = 'application/x-www-form-urlencoded';
        }

        if (isset($options['multipart'])) {
            $elements = $options['multipart'];
            unset($options['multipart']);
            $options['body'] = new Psr7\MultipartStream($elements);
        }

        if (!empty($options['decode_content'])
            && $options['decode_content'] !== true
        ) {
            $modify['set_headers']['Accept-Encoding'] = $options['decode_content'];
        }

        if (isset($options['headers'])) {
            if (isset($modify['set_headers'])) {
                $modify['set_headers'] = $options['headers'] + $modify['set_headers'];
            } else {
                $modify['set_headers'] = $options['headers'];
            }
            unset($options['headers']);
        }

        if (isset($options['body'])) {
            if (is_array($options['body'])) {
                throw new InvalidBodyException();
            }
            $modify['body'] = Psr7\stream_for($options['body']);
            unset($options['body']);
        }

        if (!empty($options['auth'])) {
            $value = $options['auth'];
            $type = is_array($value)
                ? (isset($value[2]) ? strtolower($value[2]) : 'basic')
                : $value;
            $config['auth'] = $value;
            switch (strtolower($type)) {
                case 'basic':
                    $modify['set_headers']['Authorization'] = 'Basic '
                        . base64_encode("$value[0]:$value[1]");
                    break;
                case 'digest':
                    // @todo: Do not rely on curl
                    $options['curl'][CURLOPT_HTTPAUTH] = CURLAUTH_DIGEST;
                    $options['curl'][CURLOPT_USERPWD] = "$value[0]:$value[1]";
                    break;
            }
        }

        if (isset($options['query'])) {
            $value = $options['query'];
            if (is_array($value)) {
                $value = http_build_query($value, null, '&', PHP_QUERY_RFC3986);
            }
            if (!is_string($value)) {
                throw new InvalidQueryException();
            }
            $modify['query'] = $value;
            unset($options['query']);
        }

        if (isset($options['json'])) {
            $modify['body'] = Psr7\stream_for(json_encode($options['json']));
            $options['_conditional']['Content-Type'] = 'application/json';
            unset($options['json']);
        }

        $request = Psr7\modify_request($request, $modify);
        if ($request->getBody() instanceof Psr7\MultipartStream) {
            // Use a multipart/form-data POST if a Content-Type is not set.
            $options['_conditional']['Content-Type'] = 'multipart/form-data; boundary='
                . $request->getBody()->getBoundary();
        }

        // Merge in conditional headers if they are not present.
        if (isset($options['_conditional'])) {
            // Build up the changes so it's in a single clone of the message.
            $modify = [];
            foreach ($options['_conditional'] as $k => $v) {
                if (!$request->hasHeader($k)) {
                    $modify['set_headers'][$k] = $v;
                }
            }
            $request = Psr7\modify_request($request, $modify);
            // Don't pass this internal value along to middleware/handlers.
            unset($options['_conditional']);
        }

        return $request;
    }

    /**
     * @param $method
     * @param $url
     * @param array $options
     * @return array
     * @throws TokenMissingException
     */
    public function apiCall($method, $url, $options = []) {

        if($this->accessToken === null) {
            throw new TokenMissingException();
        }

        $request = $this->getAuthenticatedRequest($method, $url, $this->getAccessToken()->getToken(), $options);
        $request = $this->applyOptions($request, $options);
        return $this->getResponse($request);
    }

    /**
     * Returns result of authorized GET request
     * @param $url
     * @param array $options
     * @return array
     */
    public function queryApiCall($url , $options = []) {
        return $this->apiCall('GET', $url, $options);
    }

    /**
     * Returns result of authorized POST request
     * @param $url
     * @param array $options
     * @return array
     */
    public function postApiCall($url , $options = []) {
        return $this->apiCall('POST', $url, $options);
    }

    /**
     * Returns result of authorized PUT request
     * @param $url
     * @param array $options
     * @return array
     */
    public function putApiCall($url , $options = []) {
        return $this->apiCall('PUT', $url, $options);
    }

    /**
     * Returns result of authorized DELETE request
     * @param $url
     * @param array $options
     * @return array
     */
    public function deleteApiCall($url , $options = []) {
        return $this->apiCall('DELETE', $url, $options);
    }

    /**
     * Gets status code
     *
     * @return int|null
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * {@inheritdoc}
     *
     * @param  RequestInterface $request
     * @return array
     */
    public function getResponse(RequestInterface $request)
    {
        $response = $this->sendRequest($request);
        $parsed = $this->parseResponse($response);

        $this->statusCode = $response->getStatusCode();
        $this->checkResponse($response, $parsed);

        return $parsed;
    }

    /**
     * Returns an array of needed options
     * @return array
     */
    protected function getPdffillerOptions()
    {
        return [
            'urlApiDomain',
        ];
    }

    /**
     * Verifies that all required options are present in array.
     *
     * @param  array $options
     * @return void
     * @throws OptionsMissingException
     */
    private function assertPdffillerOptions(array $options)
    {
        $missing = array_diff_key(array_flip($this->getPdffillerOptions()), $options);

        if (!empty($missing)) {
            throw new OptionsMissingException(array_keys($missing));
        }
    }

    /**
     * Returns an access token object
     *
     * @param string $grant
     * @param array $options
     * @return AccessToken
     */
    public function getAccessToken($grant = 'client_credentials', array $options = [])
    {
        if($this->accessToken !== null) {
            return $this->accessToken;
        }

        return $this->accessToken = parent::getAccessToken($grant, $options);
    }

    /**
     * Sets an access token of current provider object
     *
     * @param AccessToken $value
     * @return $this
     */
    public function setAccessToken(AccessToken $value) {
        $this->accessToken = $value;

        return $this;
    }

    /**
     * Checks a provider response on errors.
     *
     * @throws IdentityProviderException
     * @throws ResponseException
     * @param  ResponseInterface $response
     * @param  array|string $data Parsed response data
     * @return void
     */
    protected function checkResponse(ResponseInterface $response, $data)
    {

        if (!empty($data['errors'])) {
            $errors = $data['errors'];
            throw new ResponseException($errors);
        }

        parent::checkResponse($response, $data);
    }
}
