<?php

namespace Examples;

use League\OAuth2\Client\Token\AccessToken;
use PDFfiller\OAuth2\Client\Provider\Core\GrantType;
use PDFfiller\OAuth2\Client\Provider\PDFfiller;
use Flintstone\Flintstone;
use AdammBalogh\KeyValueStore\Adapter\FileAdapter;
use AdammBalogh\KeyValueStore\KeyValueStore;
use Carbon\Carbon;

class ExampleFabric
{
    const TIME_ZONE = 'America/New_York';
    const ACCESS_TOKEN_KEY = 'access_token';
    const REFRESH_TOKEN_KEY = 'refresh_token';

    /** @var PDFfiller */
    private $provider = null;

    /** @var string */
    private $type = null;

    /**
     * ExampleFabric constructor.
     *
     * @param GrantType $grantType
     * @param array $params
     */
    public function __construct(GrantType $grantType, $params = [])
    {
        $this->provider = new PDFfiller($params);
        $this->type = $grantType->getValue();
    }

    public function getProvider($accessTokenParams = [], $useCache = true)
    {
        if (!$useCache) {
            $this->provider->getAccessToken($this->type, $accessTokenParams);

            return $this->provider;
        }
        return $this->provider->setAccessToken($this->getToken($accessTokenParams));
    }

    private function cacheToken(AccessToken $accessToken)
    {
        $tz = self::TIME_ZONE;
        $kvs = $this->getKeyValueStorage();

        $liveTimeInSec = Carbon::createFromTimestamp($accessToken->getExpires(), $tz)->diffInSeconds(Carbon::now($tz));

        $kvs->set(self::ACCESS_TOKEN_KEY, $accessToken->getToken());
        $kvs->expire(self::ACCESS_TOKEN_KEY, $liveTimeInSec);
        $kvs->set(self::REFRESH_TOKEN_KEY, $accessToken->getRefreshToken());
    }

    private function getToken($accessTokenParams)
    {
        $kvs = $this->getKeyValueStorage();

        if ($kvs->has(self::ACCESS_TOKEN_KEY) && $kvs->has(self::REFRESH_TOKEN_KEY)) {
            return new AccessToken([
                'access_token' => $kvs->get(self::ACCESS_TOKEN_KEY),
                'expires_in' => $kvs->getTtl(self::ACCESS_TOKEN_KEY),
                'refresh_token' => $kvs->get(self::REFRESH_TOKEN_KEY),
            ]);
        }

        $accessToken = $this->provider->getAccessToken($this->type, $accessTokenParams);
        $this->cacheToken($accessToken);

        return $accessToken;
    }

    private function getKeyValueStorage()
    {
        $tmp_dir = sys_get_temp_dir() ?: ini_get('upload_tmp_dir');

        return new KeyValueStore(new FileAdapter(Flintstone::load('usersDatabase', ['dir' => $tmp_dir])));
    }
}
