<?php

namespace SamuelBednarcik\ElasticAPMAgent\Builder;

abstract class AbstractEventBuilder
{
    /**
     * @param int $bits
     * @return string
     * @throws \Exception
     */
    public static function generateRandomBitsInHex(int $bits): string
    {
        return bin2hex(random_bytes($bits/8));
    }
}
