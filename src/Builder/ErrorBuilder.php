<?php

namespace SamuelBednarcik\ElasticAPMAgent\Builder;

use SamuelBednarcik\ElasticAPMAgent\Events\Error;
use SamuelBednarcik\ElasticAPMAgent\Events\ErrorException;

class ErrorBuilder extends AbstractEventBuilder
{
    /**
     * @param \Exception $exception
     * @return Error
     * @throws \Exception
     */
    public static function createFromException(\Exception $exception)
    {
        $trace = $exception->getTrace();

        $error = new Error();
        $error->setId(self::generateRandomBitsInHex(Error::ERROR_ID_SIZE));

        $errorException = new ErrorException();
        $errorException->setCode($exception->getCode());
        $errorException->setMessage($exception->getMessage());
        $errorException->setType(get_class($exception));
        $error->setException($errorException);

        $error->setTimestamp(microtime(true) * 1000000);
        $error->setCulprit($trace[0]['class'] . $trace[0]['type'] . $trace[0]['function']);

        return $error;
    }
}
