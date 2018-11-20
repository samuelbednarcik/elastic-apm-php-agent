<?php

namespace SamuelBednarcik\ElasticAPMAgent\Builder;

use SamuelBednarcik\ElasticAPMAgent\Events\Transaction;
use SamuelBednarcik\ElasticAPMAgent\Exception\InvalidTraceContextHeaderException;
use SamuelBednarcik\ElasticAPMAgent\TraceParent;
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
}
