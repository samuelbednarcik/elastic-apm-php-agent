<?php

namespace SamuelBednarcik\ElasticAPMAgent;

class AgentConfiguration
{
    /**
     * @var string
     */
    private $serviceName;

    /**
     * @var string|null
     */
    private $secretToken;

    /**
     * @var string
     */
    private $serverUrl;

    public function __construct(string $serviceName, string $serverUrl)
    {
        $this->serviceName = $serviceName;
        $this->serverUrl = $serverUrl;
    }

    /**
     * @return string
     */
    public function getServiceName(): string
    {
        return $this->serviceName;
    }

    /**
     * @param string $serviceName
     */
    public function setServiceName(string $serviceName): void
    {
        $this->serviceName = $serviceName;
    }

    /**
     * @return null|string
     */
    public function getSecretToken(): ?string
    {
        return $this->secretToken;
    }

    /**
     * @param null|string $secretToken
     */
    public function setSecretToken(?string $secretToken): void
    {
        $this->secretToken = $secretToken;
    }

    /**
     * @return string
     */
    public function getServerUrl(): string
    {
        return $this->serverUrl;
    }

    /**
     * @param string $serverUrl
     */
    public function setServerUrl(string $serverUrl): void
    {
        $this->serverUrl = $serverUrl;
    }
}
