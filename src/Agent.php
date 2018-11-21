<?php

namespace SamuelBednarcik\ElasticAPMAgent;

use GuzzleHttp\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use SamuelBednarcik\ElasticAPMAgent\Builder\AbstractEventBuilder;
use SamuelBednarcik\ElasticAPMAgent\Builder\TransactionBuilder;
use SamuelBednarcik\ElasticAPMAgent\Events\Error;
use SamuelBednarcik\ElasticAPMAgent\Events\Span;
use SamuelBednarcik\ElasticAPMAgent\Events\Transaction;
use SamuelBednarcik\ElasticAPMAgent\Exception\AgentStateException;
use SamuelBednarcik\ElasticAPMAgent\Exception\BadEventRequestException;
use SamuelBednarcik\ElasticAPMAgent\Serializer\ElasticAPMSerializer;
use Symfony\Component\HttpFoundation\Request;

class Agent
{
    const AGENT_NAME = 'samuelbednarcik/elastic-apm-agent';
    const AGENT_VERSION = 'dev';

    /**
     * @var AgentConfiguration
     */
    private $config;

    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var ElasticAPMSerializer
     */
    private $serializer;

    /**
     * @var CollectorInterface[]
     */
    private $collectors = [];

    /**
     * @var Transaction|null
     */
    private $transaction;

    /**
     * @var Span[]
     */
    private $spans = [];

    /**
     * @var Error[]
     */
    private $errors = [];

    /**
     * @var bool
     */
    private $started = false;

    /**
     * @param AgentConfiguration $config
     * @param ClientInterface $client
     * @param ElasticAPMSerializer $serializer
     * @param CollectorInterface[] $collectors
     */
    public function __construct(
        AgentConfiguration $config,
        ClientInterface $client,
        ElasticAPMSerializer $serializer,
        array $collectors
    ) {
        $this->config = $config;
        $this->client = $client;
        $this->serializer = $serializer;
        $this->collectors = $collectors;
    }

    /**
     * @return AgentConfiguration
     */
    public function getConfig(): AgentConfiguration
    {
        return $this->config;
    }

    /**
     * @param Request|null $request
     * @return Transaction
     * @throws AgentStateException
     * @throws \Exception
     */
    public function start(Request $request = null): Transaction
    {
        if ($this->started === true) {
            throw new AgentStateException('Agent already started!');
        }

        $this->transaction = TransactionBuilder::buildFromRequest($request);
        $this->started = true;
        return $this->transaction;
    }

    /**
     * @param string $result
     * @return Transaction
     * @throws AgentStateException
     * @throws \Exception
     */
    public function stop(string $result): Transaction
    {
        if ($this->started !== true) {
            throw new AgentStateException('You have to call start method before the stop method!');
        }

        $this->transaction->setResult($result);
        $this->transaction->setDuration(
            TransactionBuilder::calculateDuration(microtime(true) * 1000000, $this->transaction->getTimestamp())
        );

        $this->spans = $this->collect();

        return $this->transaction;
    }

    /**
     * Capture an error in current transaction
     * @param Error $error
     */
    public function captureError(Error $error)
    {
        $this->errors[] = $error;
    }


    /**
     * Collect spans from registered collectors
     * @return Span[]
     * @throws \Exception
     */
    private function collect(): array
    {
        $spans = [];

        foreach ($this->collectors as $collector) {
            foreach ($collector->getSpans() as $span) {
                $spans[] = $this->prepareSpan($span);
            }
        }

        return $spans;
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws BadEventRequestException
     */
    public function sendAll(): ResponseInterface
    {
        $this->prepareTransaction();

        $request = new EventIntakeRequest($this->serializer);
        $request->setMetadata($this->config->getMetadata());
        $request->addTransaction($this->transaction);
        $request->setSpans($this->spans);
        $request->setErrors($this->errors);

        return $this->send($request);
    }

    /**
     * @param EventIntakeRequest $request
     * @return ResponseInterface
     * @throws BadEventRequestException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function send(EventIntakeRequest $request): ResponseInterface
    {
        return $this->client->request('POST', $this->config->getServerUrl() . EventIntakeRequest::INTAKE_ENDPOINT, [
            'body' => $request->getRequestBody(),
            'headers' => [
                'Content-Type' => EventIntakeRequest::CONTENT_TYPE
            ]
        ]);
    }

    /**
     * Prepare transaction for sending to APM.
     */
    private function prepareTransaction()
    {
        $this->transaction->setSpanCount([
            'started' => count($this->spans)
        ]);

        if (empty($this->transaction->getContext()) || empty($this->spans)) {
            $this->transaction->setSampled(false);
        }
    }

    /**
     * Prepare span for for sending to APM.
     * @param Span $span
     * @return Span
     * @throws \Exception
     */
    private function prepareSpan(Span $span): Span
    {
        $span->setTransactionId($this->transaction->getId());
        $span->setTraceId($this->transaction->getTraceId());

        if ($span->getId() === null) {
            $span->setId(AbstractEventBuilder::generateRandomBitsInHex(64));
        }

        if ($span->getParentId() === null) {
            $span->setParentId($this->transaction->getId());
        }

        if ($span->getStart() === null) {
            $span->setStart(
                intval(round(($span->getTimestamp() - $this->transaction->getTimestamp()) / 1000))
            );
        }

        return $span;
    }
}
