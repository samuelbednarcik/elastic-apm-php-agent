<?php

namespace SamuelBednarcik\ElasticAPMAgent\Builder;

use SamuelBednarcik\ElasticAPMAgent\Events\Transaction;
use SamuelBednarcik\ElasticAPMAgent\Exception\InvalidTraceContextHeaderException;
use SamuelBednarcik\ElasticAPMAgent\TraceParent;
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TransactionBuilder extends AbstractEventBuilder
{
    const TRANSACTION_ID_SIZE = 64;
    const TRACE_ID_SIZE = 128;
    const PARENT_ID_SIZE = 64;

    /**
     * @param Request|null $request
     * @return Transaction
     * @throws \Exception
     */
    public static function buildFromRequest(Request $request = null): Transaction
    {
        if ($request === null) {
            $request = Request::createFromGlobals();
        }

        $transaction = new Transaction();
        $transaction->setName($request->getMethod() . ' ' . $request->getPathInfo());
        $transaction->setTimestamp($request->server->get('REQUEST_TIME_FLOAT') * 1000000);
        $transaction->setType(Transaction::TYPE_REQUEST);
        $transaction->setId(self::generateRandomBitsInHex(self::TRANSACTION_ID_SIZE));

        if ($header = $request->headers->get('traceparent') !== null) {
            try {
                $traceParent = TraceParent::createFromHeader($header);
                $transaction->setTraceId($traceParent->getTraceId());
                $transaction->setParentId($traceParent->getSpanId());
            } catch (InvalidTraceContextHeaderException $e) {
                $transaction->setTraceId(self::generateRandomBitsInHex(self::TRACE_ID_SIZE));
            }
        } else {
            $transaction->setTraceId(self::generateRandomBitsInHex(self::TRACE_ID_SIZE));
        }

        return $transaction;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return array
     */
    public static function buildContext(Request $request, Response $response): array
    {
        return [
            'request' => [
                'url' => [
                    'raw' => $request->getSchemeAndHttpHost() . $request->getRequestUri(),
                    'full' => $request->getSchemeAndHttpHost() . $request->getRequestUri(),
                    'hostname' => $request->getHttpHost(),
                    'protocol' => $request->getScheme() . ":",
                    'pathname' => $request->getPathInfo(),
                    'search' => $request->getQueryString() !== null ? '?' . $request->getQueryString() : null
                ],
                'http_version' => $request->getProtocolVersion(),
                'method' => $request->getMethod(),
                'headers' => self::prepareHeaders($request->headers)
            ],
            'response' => [
                'status_code' => $response->getStatusCode(),
                'headers' => self::prepareHeaders($response->headers)
            ]
        ];
    }

    /**
     * @param Response $response
     * @return string
     */
    public static function getResultStringFromResponse(Response $response): string
    {
        return sprintf(
            'HTTP/%s %s %s',
            $response->getProtocolVersion(),
            $response->getStatusCode(),
            Response::$statusTexts[$response->getStatusCode()]
        );
    }

    /**
     * @param float Current unix timestamp in microseconds
     * @param float $transactionTimestamp
     * @return float
     */
    public static function calculateDuration(float $now, float $transactionTimestamp): float
    {
        return round(($now - $transactionTimestamp) / 1000, 3);
    }

    /**
     * Get array of headers from header bag
     *
     * @param HeaderBag $headerBag
     * @return array
     */
    private static function prepareHeaders(HeaderBag $headerBag)
    {
        $headers = $headerBag->all();
        $result = [];

        foreach ($headers as $header => $values) {
            $result[$header] = $values[0];
        }

        return $result;
    }

}
