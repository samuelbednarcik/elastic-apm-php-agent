<?php

namespace SamuelBednarcik\ElasticAPMAgent;

use GuzzleHttp\ClientInterface;
use SamuelBednarcik\ElasticAPMAgent\Events\Transaction;
use SamuelBednarcik\ElasticAPMAgent\Exception\AgentStateException;
use Symfony\Component\HttpFoundation\Request;

class Agent
{
    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var AgentConfiguration
     */
    private $config;

    /**
     * @var CollectorInterface[]
     */
    private $collectors = [];

    /**
     * @var Transaction|null
     */
    private $transaction;

    /**
     * @param AgentConfiguration $config
     * @param ClientInterface $client
     */
    public function __construct(AgentConfiguration $config, ClientInterface $client)
    {
        $this->config = $config;
        $this->client = $client;
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
}
