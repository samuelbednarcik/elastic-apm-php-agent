<?php

namespace SamuelBednarcik\ElasticAPMAgent\Events;

class Span
{
    /**
     * Hex encoded 64 random bits ID of the span
     * @var string|null
     */
    protected $id;

    /**
     * Hex encoded 64 random bits ID of the correlated transaction
     * @var string|null
     */
    protected $transactionId;

    /**
     * Hex encoded 128 random bits ID of the correlated trace
     * @var string|null
     */
    protected $traceId;

    /**
     * Hex encoded 64 random bits ID of the parent transaction or span
     * @var string|null
     */
    protected $parentId;

    /**
     * Offset relative to the transaction's timestamp identifying the start of the span, in milliseconds
     * @var int|null
     */
    protected $start;

    /**
     * Timestamp of the start of span in microseconds
     * @var float|null
     */
    protected $timestamp;

    /**
     * Duration of the span in milliseconds
     * @var int|null
     */
    protected $duration;

    /**
     * Generic designation of a span in the scope of a transaction
     * @var string|null
     */
    protected $name;

    /**
     * Keyword of specific relevance in the service's domain (eg: 'db.postgresql.query', 'template.erb', etc)
     * @var string|null
     */
    protected $type;

    /**
     * Any other arbitrary data
     * @var array|null
     */
    protected $context;

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
     * @return int|null
     */
    public function getStart(): ?int
    {
        return $this->start;
    }

    /**
     * @param null|int $start
     */
    public function setStart(?int $start): void
    {
        $this->start = $start;
    }

    /**
     * @return float|null
     */
    public function getTimestamp(): ?float
    {
        return $this->timestamp;
    }

    /**
     * @param float|null $timestamp
     */
    public function setTimestamp(?float $timestamp): void
    {
        $this->timestamp = $timestamp;
    }

    /**
     * @return int|null
     */
    public function getDuration(): ?int
    {
        return $this->duration;
    }

    /**
     * @param int|null $duration
     */
    public function setDuration(?int $duration): void
    {
        $this->duration = $duration;
    }

    /**
     * @return null|string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param null|string $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return null|string
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param null|string $type
     */
    public function setType(?string $type): void
    {
        $this->type = $type;
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
}
