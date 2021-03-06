<?php

namespace SamuelBednarcik\ElasticAPMAgent;

use SamuelBednarcik\ElasticAPMAgent\Events\Metadata;

class AgentConfiguration
{
    /**
     * @var string|null
     */
    private $serviceName;

    /**
     * @var string|null
     */
    private $secretToken;

    /**
     * @var Metadata|null
     */
    private $metadata;

    /**
     * @var string|null
     */
    private $serverUrl;

    /**
     * @return null|string
     */
    public function getServiceName(): ?string
    {
        return $this->serviceName;
    }

    /**
     * @param null|string $serviceName
     */
    public function setServiceName(?string $serviceName): void
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
     * @return null|Metadata
     */
    public function getMetadata(): ?Metadata
    {
        return $this->metadata;
    }

    /**
     * @param null|Metadata $metadata
     */
    public function setMetadata(?Metadata $metadata): void
    {
        $this->metadata = $metadata;
    }

    /**
     * @return null|string
     */
    public function getServerUrl(): ?string
    {
        return $this->serverUrl;
    }

    /**
     * @param null|string $serverUrl
     */
    public function setServerUrl(?string $serverUrl): void
    {
        $this->serverUrl = $serverUrl;
    }
}
