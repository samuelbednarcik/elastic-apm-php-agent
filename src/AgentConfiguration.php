<?php

namespace SamuelBednarcik\ElasticAPMAgent;

use SamuelBednarcik\ElasticAPMAgent\Events\Metadata;

class AgentConfiguration
{
    /** @var string */
    private $serviceName;

    /** @var string */
    private $serverUrl;

    /** @var Metadata */
    private $metadata;

    /** @var string|null */
    private $secretToken;

    public function __construct(
        string $serviceName,
        string $serverUrl,
        Metadata $metadata
    ) {
        $this->serviceName = $serviceName;
        $this->serverUrl = $serverUrl;
        $this->metadata = $metadata;
    }

    public function getServiceName(): string
    {
        return $this->serviceName;
    }

    public function getServerUrl(): string
    {
        return $this->serverUrl;
    }

    public function getMetadata(): Metadata
    {
        return $this->metadata;
    }

    public function getSecretToken(): ?string
    {
        return $this->secretToken;
    }

    public function setSecretToken(?string $secretToken): void
    {
        $this->secretToken = $secretToken;
    }
}
