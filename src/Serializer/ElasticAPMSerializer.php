<?php

namespace SamuelBednarcik\ElasticAPMAgent\Serializer;

use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class ElasticAPMSerializer extends Serializer
{
    public function __construct()
    {
        $objectNormalizer = new ObjectNormalizer(
            null,
            new CamelCaseToSnakeCaseNameConverter(),
            null,
            new ReflectionExtractor()
        );

        $normalizers = [
            $objectNormalizer
        ];

        $encoders = [
            new JsonEncoder()
        ];

        parent::__construct($normalizers, $encoders);
    }
}
