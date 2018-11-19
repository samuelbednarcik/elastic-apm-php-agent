<?php

namespace SamuelBednarcik\ElasticAPMAgent\Events;

class Transaction
{
    const TYPE_REQUEST = 'request';
    const TYPE_BACKGROUND_JOB = 'backgroundjob';

    /**
     * @var array|null
     */
    protected $context;

    /**
     * @var float|null
     */
    protected $duration;

    /**
     * @var string|null
     */
    protected $name;

    /**
     * @var string|null
     */
    protected $result;

    /**
     * @var string|null
     */
    protected $type;

    /**
     * @var bool|null
     */
    protected $sampled;

    /**
     * @var string|null
     */
    protected $id;

    /**
     * @var string|null
     */
    protected $traceId;

    /**
     * @var string|null
     */
    protected $parentId;

    /**
     * @var array|null
     */
    protected $spanCount;

    /**
     * @var array|null
     */
    protected $marks;

    /**
     * @var float|null
     */
    protected $timestamp;

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
     * @return float|null
     */
    public function getDuration(): ?float
    {
        return $this->duration;
    }

    /**
     * @param float|null $duration
     */
    public function setDuration(?float $duration): void
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
    public function getResult(): ?string
    {
        return $this->result;
    }

    /**
     * @param null|string $result
     */
    public function setResult(?string $result): void
    {
        $this->result = $result;
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
     * @return bool|null
     */
    public function getSampled(): ?bool
    {
        return $this->sampled;
    }

    /**
     * @param bool|null $sampled
     */
    public function setSampled(?bool $sampled): void
    {
        $this->sampled = $sampled;
    }

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
     * @return array|null
     */
    public function getSpanCount(): ?array
    {
        return $this->spanCount;
    }

    /**
     * @param array|null $spanCount
     */
    public function setSpanCount(?array $spanCount): void
    {
        $this->spanCount = $spanCount;
    }

    /**
     * @return array|null
     */
    public function getMarks(): ?array
    {
        return $this->marks;
    }

    /**
     * @param array|null $marks
     */
    public function setMarks(?array $marks): void
    {
        $this->marks = $marks;
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

}
