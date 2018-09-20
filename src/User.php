<?php

namespace PDFfiller\OAuth2\Client\Provider;

use PDFfiller\OAuth2\Client\Provider\Core\Exception;
use PDFfiller\OAuth2\Client\Provider\Core\Model;

/**
 * Class User
 * @package PDFfiller\OAuth2\Client\Provider
 * @property string $id
 * @property string $email
 * @property string $avatar
 */
class User extends Model
{
    const ME = 'me';

    /**
     * @param $provider
     * @return mixed
     * @throws Exceptions\InvalidQueryException
     * @throws Exceptions\InvalidRequestException
     */
    public static function me($provider)
    {
        return static::query($provider, [ self::ME]);
    }

    /**
     * @return mixed
     * @throws Exceptions\InvalidQueryException
     * @throws Exceptions\InvalidRequestException
     */
    public function getMeInfo()
    {
        return self::me($this->client);
    }

    /**
     * @inheritdoc
     */
    protected function create($options = [])
    {
        throw new Exception("Can't create user,.");
    }

    /**
     * @inheritdoc
     */
    protected function update($options = [])
    {
        throw new Exception("Can't update user");
    }

    /**
     * @inheritdoc
     */
    public function save($options = [])
    {
        throw new Exception("Can't save user");
    }
}
