<?php

namespace PDFfiller\OAuth2\Client\Provider;

use PDFfiller\OAuth2\Client\Provider\Grant\InternalGrant;
use League\OAuth2\Client\Provider\GenericProvider;
use InvalidArgumentException;
use League\Uri\Schemes\Http as HttpUri;
use League\Uri\Modifiers\Resolve;
use Psr\Http\Message\RequestInterface;
use \GuzzleHttp\Psr7 as Psr7;

/**
 * Represents a generic service provider that may be used to interact with any
 * OAuth 2.0 service provider, using Bearer token authentication.
 */
class PDFfiller extends GenericProvider
{
    private $urlApiDomain;
    private $accessTokenHash;
    private $statusCode;

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
        // init here
        parent::__construct($options, $collaborators);
        // then init new grant
        $this->getGrantFactory()->setGrant(InternalGrant::NAME, new InternalGrant());
    }

    public function getAuthenticatedRequest($method, $url, $token, array $options = [])
    {
        $baseUri     = HttpUri::createFromString($this->urlApiDomain);
        $relativeUri = HttpUri::createFromString($url);
        $modifier    = new Resolve($baseUri);
        $newUri = $modifier->__invoke($relativeUri);
        return parent::getAuthenticatedRequest($method, $newUri, $token, $options);
    }

    /**
     * Applies the array of request options to a request.
     *
     * @param RequestInterface $request
     * @param array            $options
     *
     * @return RequestInterface
     */
    private function applyOptions(RequestInterface $request, array &$options)
    {
        $modify = [];

        if (isset($options['form_params'])) {
            if (isset($options['multipart'])) {
                throw new \InvalidArgumentException('You cannot use '
                    . 'form_params and multipart at the same time. Use the '
                    . 'form_params option if you want to send application/'
                    . 'x-www-form-urlencoded requests, and the multipart '
                    . 'option to send multipart/form-data requests.');
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
                $this->invalidBody();
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
                throw new \InvalidArgumentException('query must be a string or array');
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
    private function invalidBody()
    {
        throw new \InvalidArgumentException('Passing in the "body" request '
            . 'option as an array to send a POST request has been deprecated. '
            . 'Please use the "form_params" request option to send a '
            . 'application/x-www-form-urlencoded request, or a the "multipart" '
            . 'request option to send a multipart/form-data request.');
    }

    public function apiCall($method, $url, $options = []) {

        if($this->accessTokenHash === null) {
            throw new InvalidArgumentException(
                'You did not set access token'
            );
        }

        $request = $this->getAuthenticatedRequest($method, $url, $this->getAccessToken(), $options);
        $request = $this->applyOptions($request, $options);
        return $this->getResponse($request);
    }

    public function queryApiCall($url , $options = []) {
        return $this->apiCall('GET', $url, $options);
    }

    public function postApiCall($url , $options = []) {
        return $this->apiCall('POST', $url, $options);
    }

    public function putApiCall($url , $options = []) {
        return $this->apiCall('PUT', $url, $options);
    }

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
     * @return mixed
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
     * @return array
     */
    protected function getPdffillerOptions()
    {
        return [
            'urlApiDomain',
        ];
    }

    /**
     * Verifies that all required options have been passed.
     *
     * @param  array $options
     * @return void
     * @throws InvalidArgumentException
     */
    private function assertPdffillerOptions(array $options)
    {
        $missing = array_diff_key(array_flip($this->getPdffillerOptions()), $options);

        if (!empty($missing)) {
            throw new InvalidArgumentException(
                'Required options not defined: ' . implode(', ', array_keys($missing))
            );
        }
    }

    public function getAccessToken($grant = 'client_credentials', array $options = [])
    {
        if($this->accessTokenHash !== null) {
            return $this->accessTokenHash;
        }
        return parent::getAccessToken($grant, $options);
    }

    public function setAccessTokenHash($value) {
        $this->accessTokenHash = $value;
    }
}
