<?php

namespace PDFfiller\OAuth2\Client\Provider\Core;

/**
 * Class ExceptionsMessages
 *
 * @package PDFfiller\OAuth2\Client\Provider\Core
 */
class ExceptionsMessages
{
    /** @var Exception */
    private $exception;

    public function __construct(Exception $exception)
    {
        $this->setException($exception);
    }

    /**
     * @return Exception
     */
    public function getException(): Exception
    {
        return $this->exception;
    }

    /**
     * @param Exception $exception
     */
    public function setException(Exception $exception)
    {
        $this->exception = $exception;
    }

    /**
     * @param string $locale
     * @return string
     */
    public function getMessage($locale = "en")
    {
        $className = (new \ReflectionClass($this->exception))->getShortName();
        $class = substr($className, 0, strpos($className, 'Exception'));
        $class = lcfirst($class) ?: "default";

        $messages = self::getMessages($locale);

        return $messages[$class] ?: (ucfirst($class) . "Exception");
    }

    /**
     * Returns an array of possible exceptions messages
     *
     * @param string $locale
     * @return array
     */
    protected function getMessages($locale = "en")
    {
        $path = __DIR__ . "/../Messages/" . $locale . "/messages.json";

        if (file_exists($path)) {
            $jsonMessages = file_get_contents($path);

            return json_decode($jsonMessages, true);
        }

        return [];
    }
}
