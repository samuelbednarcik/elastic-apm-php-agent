<?php

namespace SamuelBednarcik\ElasticAPMAgent\Events;

class Metadata
{
    /**
     * @var array|null
     */
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
}
