<?php

namespace SamuelBednarcik\ElasticAPMAgent\Builder;

use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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

    /**
     * @param Request $request
     * @param Response $response
     * @return array
     */
    public static function buildContext(Request $request, Response $response): array
    {
        return [
            'request' => [
                'url' => [
                    'raw' => $request->getSchemeAndHttpHost() . $request->getRequestUri(),
                    'full' => $request->getSchemeAndHttpHost() . $request->getRequestUri(),
                    'hostname' => $request->getHttpHost(),
                    'protocol' => $request->getScheme() . ":",
                    'pathname' => $request->getPathInfo(),
                    'search' => $request->getQueryString() !== null ? '?' . $request->getQueryString() : null
                ],
                'http_version' => $request->getProtocolVersion(),
                'method' => $request->getMethod(),
                'headers' => self::prepareHeaders($request->headers)
            ],
            'response' => [
                'status_code' => $response->getStatusCode(),
                'headers' => self::prepareHeaders($response->headers)
            ]
        ];
    }

    /**
     * Get array of headers from header bag
     *
     * @param HeaderBag $headerBag
     * @return array
     */
    private static function prepareHeaders(HeaderBag $headerBag)
    {
        $headers = $headerBag->all();
        $result = [];

        foreach ($headers as $header => $values) {
            $result[$header] = $values[0];
        }

        return $result;
    }
}
