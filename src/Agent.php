<?php

namespace SamuelBednarcik\ElasticAPMAgent;

use GuzzleHttp\ClientInterface;
use SamuelBednarcik\ElasticAPMAgent\Events\Span;
use SamuelBednarcik\ElasticAPMAgent\Events\Transaction;
use SamuelBednarcik\ElasticAPMAgent\Exception\AgentStateException;
use SamuelBednarcik\ElasticAPMAgent\Serializer\ElasticAPMSerializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

class Agent
{
    const AGENT_NAME = 'samuelbednarcik/elastic-apm-agent';
    const AGENT_VERSION = 'dev';
    const INTAKE_ENDPOINT = '/intake/v2/events';

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
     * @param AgentConfiguration $config
     * @param ClientInterface $client
     * @param ElasticAPMSerializer $serializer
     */
    public function __construct(AgentConfiguration $config, ClientInterface $client, ElasticAPMSerializer $serializer)
    {
        $this->config = $config;
        $this->client = $client;
        $this->serializer = $serializer;
    }

    /**
     * @return AgentConfiguration
     */
    public function getConfig(): AgentConfiguration
    {
        return $this->config;
    }

    /**
     * @param CollectorInterface $collector
     */
    public function registerCollector(CollectorInterface $collector)
    {
        $this->collectors[] = $collector;
    }

    /**
     * @param Request|null $request
     * @return Transaction
     * @throws AgentStateException
     * @throws \Exception
     */
    public function start(Request $request = null): Transaction
    {
        if ($this->transaction !== null) {
            throw new AgentStateException('Agent already started!');
        }

        $this->transaction = TransactionBuilder::buildFromRequest($request);
        return $this->transaction;
    }

    /**
     * @param string $result
     * @return Transaction
     * @throws AgentStateException
     */
    public function stop(string $result): Transaction
    {
        if ($this->transaction === null) {
            throw new AgentStateException('You have to call start method before the stop method!');
        }

        $this->transaction->setResult($result);
        $this->transaction->setDuration(
            TransactionBuilder::calculateDuration(microtime(true) * 1000000, $this->transaction->getTimestamp())
        );
        return $this->transaction;
    }

    /**
     * @throws AgentStateException
     */
    public function collect()
    {
        if ($this->transaction === null) {
            throw new AgentStateException('You can collect spans only after you stop the transaction!');
        }

        foreach ($this->collectors as $collector) {
            foreach ($collector->getSpans() as $span) {
                $span->setTransactionId($this->transaction->getId());
                $span->setTraceId($this->transaction->getTraceId());

                if ($span->getParentId() === null) {
                    $span->setParentId($this->transaction->getId());
                }

                $this->spans[] = $span;
            }
        }
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function sendAll()
    {
        $this->transaction->setSpanCount([
            'started' => count($this->spans)
        ]);

        if (empty($this->transaction->getContext()) || empty($this->spans)) {
            $this->transaction->setSampled(false);
        }

        return $this->client->request('POST', $this->config->getServerUrl() . self::INTAKE_ENDPOINT, [
            'body' => $this->prepareRequestBody(),
            'headers' => [
                'Content-Type' => 'application/x-ndjson',
            ]
        ]);
    }

    /**
     * Create request NDJSON from the data
     *
     * @return string
     */
    private function prepareRequestBody(): string
    {
        $json = $this->serializer->encode(
            ['metadata' => $this->serializer->normalize($this->config->getMetadata())],
            JsonEncoder::FORMAT
        );

        $json .= "\n";

        $json .= $this->serializer->encode(
            ['transaction' => $this->serializer->normalize($this->transaction)],
            JsonEncoder::FORMAT
        );

        foreach ($this->spans as $span) {
            $json .= "\n";
            $json .= $this->serializer->encode(
                ['span' => $this->serializer->normalize($span)],
                JsonEncoder::FORMAT
            );
        }

        return $json;
    }
}
