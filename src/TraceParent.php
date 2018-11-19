<?php

namespace SamuelBednarcik\ElasticAPMAgent;

use SamuelBednarcik\ElasticAPMAgent\Exception\InvalidTraceContextHeaderException;

class TraceParent
{
    /**
     * @var string
     */
    private $traceId;

    /**
     * @var string
     */
    private $spanId;

    /**
     * @var string
     */
    private $traceFlags;

    /**
     * @param string $traceId
     * @param string $spanId
     * @param string $traceFlags
     */
    public function __construct(string $traceId, string $spanId, string $traceFlags)
    {
        $this->traceId = $traceId;
        $this->spanId = $spanId;
        $this->traceFlags = $traceFlags;
    }

    /**
     * @param string $header
     * @return bool
     */
    public static function isValidHeader(string $header)
    {
        return preg_match('/^00-[\da-f]{32}-[\da-f]{16}-[\da-f]{2}$/', $header) === 1;
    }

    /**
     * @param string $header
     * @return TraceParent
     * @throws InvalidTraceContextHeaderException
     */
    public static function createFromHeader(string $header)
    {
        if (!self::isValidHeader($header)) {
            throw new InvalidTraceContextHeaderException();
        }

        $parsed = explode('-', $header);
        return new TraceParent($parsed[1], $parsed[2], $parsed[3]);
    }
}
