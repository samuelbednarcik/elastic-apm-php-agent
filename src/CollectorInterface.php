<?php

namespace SamuelBednarcik\ElasticAPMAgent;

use SamuelBednarcik\ElasticAPMAgent\Events\Span;

interface CollectorInterface
{
    /**
     * @return Span[]
     */
    public function getSpans(): array;
}
