<?php

namespace SamuelBednarcik\ElasticAPMAgent\Builder;

use SamuelBednarcik\ElasticAPMAgent\Agent;

class MetadataBuilder extends AbstractEventBuilder
{
    /**
     * @param string $name
     * @param string|null $version
     * @param string|null $environment
     * @param string|null $framework
     * @return array
     */
    public static function buildService(
        string $name,
        string $version = null,
        string $environment = null,
        string $framework = null
    ): array {
        return [
            'agent' => [
                'name' => Agent::AGENT_NAME,
                'version' => Agent::AGENT_VERSION
            ],
            'framework' => $framework,
            'language' => [
                'name' => 'PHP',
                'version' => phpversion()
            ],
            'name' => $name,
            'environment' => $environment,
            'version' => $version,
        ];
    }
}
