<?php

namespace SamuelBednarcik\ElasticAPMAgent\Events;

class Metadata
{
    /** @var array */
    protected $service;

    /**
     * @var array|null
     */
    protected $process;

    /**
     * @var array|null
     */
    protected $system;

    /**
     * @var array|null
     */
    protected $user;

    public function __construct(array $service)
    {
        $this->service = $service;
    }

    public function getService(): array
    {
        return $this->service;
    }

    /**
     * @return array|null
     */
    public function getProcess(): ?array
    {
        return $this->process;
    }

    /**
     * @param array|null $process
     */
    public function setProcess(?array $process): void
    {
        $this->process = $process;
    }

    /**
     * @return array|null
     */
    public function getSystem(): ?array
    {
        return $this->system;
    }

    /**
     * @param array|null $system
     */
    public function setSystem(?array $system): void
    {
        $this->system = $system;
    }

    /**
     * @return array|null
     */
    public function getUser(): ?array
    {
        return $this->user;
    }

    /**
     * @param array|null $user
     */
    public function setUser(?array $user): void
    {
        $this->user = $user;
    }
}
