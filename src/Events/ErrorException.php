<?php

namespace SamuelBednarcik\ElasticAPMAgent\Events;

class ErrorException
{
    /**
     * @var string|null
     */
    protected $code;

    /**
     * @var string|null
     */
    protected $message;

    /**
     * @var string|null
     */
    protected $module;

    /**
     * @var array|null
     */
    protected $attributes;

    /**
     * @var string|null
     */
    protected $type;

    /**
     * @var bool|null
     */
    protected $handled;

    /**
     * @return null|string
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @param null|string $code
     */
    public function setCode(?string $code): void
    {
        $this->code = $code;
    }

    /**
     * @return null|string
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * @param null|string $message
     */
    public function setMessage(?string $message): void
    {
        $this->message = $message;
    }

    /**
     * @return null|string
     */
    public function getModule(): ?string
    {
        return $this->module;
    }

    /**
     * @param null|string $module
     */
    public function setModule(?string $module): void
    {
        $this->module = $module;
    }

    /**
     * @return array|null
     */
    public function getAttributes(): ?array
    {
        return $this->attributes;
    }

    /**
     * @param array|null $attributes
     */
    public function setAttributes(?array $attributes): void
    {
        $this->attributes = $attributes;
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
    public function getHandled(): ?bool
    {
        return $this->handled;
    }

    /**
     * @param bool|null $handled
     */
    public function setHandled(?bool $handled): void
    {
        $this->handled = $handled;
    }
}
