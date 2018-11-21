<?php

namespace SamuelBednarcik\ElasticAPMAgent\Tracing;

use Psr\Http\Message\RequestInterface;
use SamuelBednarcik\ElasticAPMAgent\Agent;
use SamuelBednarcik\ElasticAPMAgent\TraceParent;

class TracingGuzzleMiddleware
{
    /**
     * @var Agent
     */
    private $agent;

    public function __construct(Agent $agent)
    {
        $this->agent = $agent;
    }

    /**
     * @param callable $handler
     * @return \Closure
     */
    public function __invoke(callable $handler)
    {
        return function (RequestInterface $request, array $options) use ($handler) {
            $transaction = $this->agent->getTransaction();

            if ($transaction !== null) {
                if ($transaction->getTraceId() !== null && $transaction->getId() !== null) {
                    $header = new TraceParent($transaction->getTraceId(), $transaction->getId(), '01');
                    $request = $request->withHeader(TraceParent::HEADER_NAME, $header->__toString());
                }
            }

            return $handler($request, $options);
        };
    }
}
