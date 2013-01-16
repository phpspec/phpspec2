<?php

namespace PHPSpec2\Looper;

class Looper
{
    private $callable;

    public function __construct($callable = null)
    {
        $this->callable = $callable;
    }

    public function __call($method, array $arguments)
    {
        return call_user_func($this->callable, $method, $arguments);
    }
}
