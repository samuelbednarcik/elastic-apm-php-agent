<?php

namespace SamuelBednarcik\ElasticAPMAgent;

use SamuelBednarcik\ElasticAPMAgent\Events\Error;
use SamuelBednarcik\ElasticAPMAgent\Events\Metadata;
use SamuelBednarcik\ElasticAPMAgent\Events\Span;
use SamuelBednarcik\ElasticAPMAgent\Events\Transaction;
use SamuelBednarcik\ElasticAPMAgent\Exception\BadEventRequestException;
use SamuelBednarcik\ElasticAPMAgent\Serializer\ElasticAPMSerializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

class EventRequest
{
    const INTAKE_ENDPOINT = '/intake/v2/events';
    const CONTENT_TYPE = 'application/x-ndjson';

    /**
     * @var ElasticAPMSerializer
     */
    private $serializer;

    /**
     * @var Metadata
     */
    private $metadata;

    /**
     * @var Transaction[]
     */
    private $transactions;

    /**
     * @var Span[]
     */
    private $spans;

    /**
     * @var Error[]
     */
    private $errors;

    /**
     * @param ElasticAPMSerializer $serializer
     */
    public function __construct(ElasticAPMSerializer $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @return Metadata
     */
    public function getMetadata(): Metadata
    {
        return $this->metadata;
    }

    /**
     * @param Metadata $metadata
     */
    public function setMetadata(Metadata $metadata): void
    {
        $this->metadata = $metadata;
    }

    /**
     * @return Transaction[]
     */
    public function getTransactions(): array
    {
        return $this->transactions;
    }

    /**
     * @param Transaction[] $transactions
     */
    public function setTransactions(array $transactions): void
    {
        $this->transactions = $transactions;
    }

    /**
     * @param Transaction $transaction
     */
    public function addTransaction(Transaction $transaction): void
    {
        $this->transactions[] = $transaction;
    }

    /**
     * @return Span[]
     */
    public function getSpans(): array
    {
        return $this->spans;
    }

    /**
     * @param Span[] $spans
     */
    public function setSpans(array $spans): void
    {
        $this->spans = $spans;
    }

    /**
     * @param Span $span
     */
    public function addSpan(Span $span): void
    {
        $this->spans[] = $span;
    }

    /**
     * @return Error[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @param Error[] $errors
     */
    public function setErrors(array $errors): void
    {
        $this->errors = $errors;
    }

    /**
     * @param Error $error
     */
    public function addError(Error $error): void
    {
        $this->errors[] = $error;
    }

    /**
     * Returns NDJSON request body
     * @throws BadEventRequestException
     */
    public function getRequestBody(): string
    {
        if ($this->metadata === null) {
            throw new BadEventRequestException('Metadata must be defined!');
        }

        $json = $this->serializer->encode(
            ['metadata' => $this->serializer->normalize($this->metadata)],
            JsonEncoder::FORMAT
        );

        $json .= "\n";

        foreach ($this->transactions as $transaction) {
            $json .= "\n";
            $json .= $this->serializer->encode(
                ['span' => $this->serializer->normalize($transaction)],
                JsonEncoder::FORMAT
            );
        }

        $json .= "\n";

        foreach ($this->spans as $span) {
            $json .= "\n";
            $json .= $this->serializer->encode(
                ['span' => $this->serializer->normalize($span)],
                JsonEncoder::FORMAT
            );
        }

        $json .= "\n";

        foreach ($this->errors as $error) {
            $json .= "\n";
            $json .= $this->serializer->encode(
                ['span' => $this->serializer->normalize($error)],
                JsonEncoder::FORMAT
            );
        }

        return $json;
    }
}
