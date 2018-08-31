<?php

namespace PDFfiller\OAuth2\Client\Provider\Core;

use PDFfiller\OAuth2\Client\Provider\Contracts\Arrayable;
use PDFfiller\OAuth2\Client\Provider\Contracts\Async;
use PDFfiller\OAuth2\Client\Provider\Contracts\Stringable;
use PDFfiller\OAuth2\Client\Provider\Exceptions\JobAlreadyRunningException;

class Job implements Async, Arrayable, Stringable
{
    /**
     * @var Model
     */
    private $model;
    /**
     * @var string
     */
    private $method;

    /**
     * @var array
     */
    private $arguments = [];

    /**
     * @var bool
     */
    private $isReady = self::NOT_READY;

    /**
     * @var Arrayable|Stringable
     */
    private $result;

    public function __construct(Model $model)
    {
        $this->model = clone $model;
    }

    /**
     * @return bool
     */
    public function isReady(): bool
    {
        $this->call();

        return $this->isReady;
    }

    /**
     * @param string $method
     * @param array $arguments
     * @return mixed
     * @throws JobAlreadyRunningException
     */
    public function __call(string $method, array $arguments)
    {
        if (!empty($this->method)) {
            throw new JobAlreadyRunningException();
        }

        $this->method = $method;

        $this->arguments = $arguments;

        return $this->call();
    }

    /**
     * @return null|Arrayable|Stringable
     */
    private function call()
    {
        if (empty($this->method)) {
            return null;
        }

        $this->result = $this->model->{$this->method}(...$this->arguments);

        $statusCode = $this->model->getClient()->getStatusCode();


        if ($statusCode === self::HTTP_STATUS_CODE_READY) {
            $this->isReady = self::READY;
        }

        return $this->getResult();
    }

    /**
     * @return Arrayable|Stringable
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->getResult()->toArray();
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getResult()->__toString();
    }
}
