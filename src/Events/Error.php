<?php

namespace SamuelBednarcik\ElasticAPMAgent\Events;

class Error
{
    const ERROR_ID_SIZE = 128;

    /**
     * Hex encoded 128 random bits ID of the error.
     * @var string|null
     */
    protected $id;

    /**
     * Hex encoded 128 random bits ID of the correlated trace.
     * Must be present if transaction_id and parent_id are set.
     * @var string|null
     */
    protected $traceId;

    /**
     * Hex encoded 64 random bits ID of the correlated transaction.
     * Must be present if trace_id and parent_id are set.
     * @var string|null
     */
    protected $transactionId;

    /**
     * Hex encoded 64 random bits ID of the parent transaction or span.
     * Must be present if trace_id and transaction_id are set.
     * @var string|null
     */
    protected $parentId;

    /**
     * @var array|null
     */
    protected $context;

    /**
     * Function call which was the primary perpetrator of this event.
     * @var string|null
     */
    protected $culprit;

    /**
     * Information about the originally thrown error.
     * @var ErrorException|null
     */
    protected $exception;

    /**
     * Additional information added when logging the error.
     * @var array|null
     */
    protected $log;

    /**
     * Timestamp in microseconds.
     * @var int|null
     */
    protected $timestamp;

    /**
     * @return null|string
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param null|string $id
     */
    public function setId(?string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return null|string
     */
    public function getTraceId(): ?string
    {
        return $this->traceId;
    }

    /**
     * @param null|string $traceId
     */
    public function setTraceId(?string $traceId): void
    {
        $this->traceId = $traceId;
    }

    /**
     * @return null|string
     */
    public function getTransactionId(): ?string
    {
        return $this->transactionId;
    }

    /**
     * @param null|string $transactionId
     */
    public function setTransactionId(?string $transactionId): void
    {
        $this->transactionId = $transactionId;
    }

    /**
     * @return null|string
     */
    public function getParentId(): ?string
    {
        return $this->parentId;
    }

    /**
     * @param null|string $parentId
     */
    public function setParentId(?string $parentId): void
    {
        $this->parentId = $parentId;
    }

    /**
     * @return null|string
     */
    public function getCulprit(): ?string
    {
        return $this->culprit;
    }

    /**
     * @param null|string $culprit
     */
    public function setCulprit(?string $culprit): void
    {
        $this->culprit = $culprit;
    }

    /**
     * @return array|null
     */
    public function getContext(): ?array
    {
        return $this->context;
    }

    /**
     * @param array|null $context
     */
    public function setContext(?array $context): void
    {
        $this->context = $context;
    }

    /**
     * @return ErrorException|null
     */
    public function getException(): ?ErrorException
    {
        return $this->exception;
    }

    /**
     * @param ErrorException|null $exception
     */
    public function setException(?ErrorException $exception): void
    {
        $this->exception = $exception;
    }

    /**
     * @return array|null
     */
    public function getLog(): ?array
    {
        return $this->log;
    }

    /**
     * @param array|null $log
     */
    public function setLog(?array $log): void
    {
        $this->log = $log;
    }

    /**
     * @return int|null
     */
    public function getTimestamp(): ?int
    {
        return $this->timestamp;
    }

    /**
     * @param int|null $timestamp
     */
    public function setTimestamp(?int $timestamp): void
    {
        $this->timestamp = $timestamp;
    }
}
