<?php

namespace Framework\Template\Php;

class SimpleFunction
{
    public $name;
    public $callback;
    public $needRenderer;

    /**
     * SimpleFunction constructor.
     *
     * @param string   $name
     * @param callable $callback
     * @param bool     $needRenderer
     */
    public function __construct(string $name, callable $callback, bool $needRenderer = false)
    {
        $this->name = $name;
        $this->callback = $callback;
        $this->needRenderer = $needRenderer;
    }
}
