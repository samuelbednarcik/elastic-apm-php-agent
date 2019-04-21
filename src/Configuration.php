<?php

declare(strict_types=1);

namespace SamuelBednarcik\ElasticAPMAgent;

use SamuelBednarcik\ElasticAPMAgent\Builder\MetadataBuilder;
use SamuelBednarcik\ElasticAPMAgent\Events\Metadata;

final class Configuration
{
    /** @var string */
    private $serviceName;

    /** @var string */
    private $serverUrl;

    /** @var string|null */
    private $version;

    /** @var string|null */
    private $environment;

    /** @var string|null */
    private $framework;

    public function __construct(
        string $serviceName,
        string $serverUrl,
        ?string $version = null,
        ?string $environment = null,
        ?string $framework = null
    ) {
        $this->serviceName = $serviceName;
        $this->serverUrl = $serverUrl;
        $this->version = $version;
        $this->environment = $environment;
        $this->framework = $framework;
    }

    public function getAgentConfiguration(): AgentConfiguration
    {
        $metadata = new Metadata(
            MetadataBuilder::buildService($this->serviceName, $this->version, $this->environment, $this->framework)
        );

        return new AgentConfiguration(
            $this->serviceName,
            $this->serverUrl,
            $metadata
        );
    }
}
