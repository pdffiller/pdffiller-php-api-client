<?php

namespace Examples;

use aslikeyou\OAuth2\Client\Provider\Pdffiller;
use Flintstone\Flintstone;
use AdammBalogh\KeyValueStore\Adapter\FileAdapter;
use AdammBalogh\KeyValueStore\KeyValueStore;
use Carbon\Carbon;

class ExampleFabric {
    const CLIENT_CRIDENTIALS_GRANT = 0;
    const PASSWORD_GRANT = 1;
    const INTERNAL_GRANT = 2;

    protected static $names = [
        self::CLIENT_CRIDENTIALS_GRANT => 'client_credentials',
        self::PASSWORD_GRANT => 'password',
        self::INTERNAL_GRANT => 'internal',
    ];

    protected static $consts = [
        self::CLIENT_CRIDENTIALS_GRANT => ['clientId','clientSecret','urlAccessToken','urlApiDomain'],
        self::PASSWORD_GRANT => ['username', 'password', 'clientId','clientSecret','urlAccessToken','urlApiDomain'],
        self::INTERNAL_GRANT=> ['username', 'password', 'clientId','clientSecret','urlAccessToken','urlApiDomain']
    ];

    public static function getProvider($type, $params, $accessTokenParams = []) {
        $provider = new Pdffiller($params);

        $tz = 'America/New_York';

        $kvs = new KeyValueStore(new FileAdapter(Flintstone::load('usersDatabase', ['dir' => '/tmp'])));
        $accessTokenKey = 'access_token';

        if (!$kvs->has($accessTokenKey)) {
            $accessToken = $provider->getAccessToken(self::$names[$type], $accessTokenParams);

            $liveTimeInSec = Carbon::createFromTimestamp(
                $accessToken->getExpires(),
                $tz
            )->diffInSeconds(Carbon::now($tz));

            $kvs->set($accessTokenKey, $accessToken->getToken());
            $kvs->expire($accessTokenKey, $liveTimeInSec);
            $accessTokenString = $accessToken->getToken();
        } else {
            $accessTokenString = $kvs->get($accessTokenKey);
        }

        $provider->setAccessTokenHash($accessTokenString);

        return $provider;
    }
}