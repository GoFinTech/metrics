<?php

namespace GoFinTech\Metrics;

use \DateTime;

class MetricsEvent implements \ArrayAccess
{

    private $code;
    private $timestamp;
    private $props;

    public function __construct(string $code, DateTime $timestamp = null)
    {
        $this->code = $code;
        $this->timestamp = $timestamp ?? new DateTime();
        $this->props = [];
    }

    public function code(): string
    {
        return $this->code;
    }

    public function timestamp(): DateTime
    {
        return $this->timestamp;
    }

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            trigger_error("Can't push nameless property to MetricsEvent");
        } else {
            $this->props[$offset] = $value;
        }
    }

    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->props);
    }

    public function offsetUnset($offset)
    {
        unset($this->props[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->props[$offset];
    }
}
