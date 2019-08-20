<?php


namespace Framework\Http\Router;

/**
 * Class Route
 * it used instead of ordinary array
 *
 * @package Framework\Http\Router
 */
class Route
{
    public $name;
    public $pattern;
    public $handler;
    public $tokens;
    public $methods;

    /**
     * Route constructor.
     * @param string $name
     * @param string $pattern
     * @param mixed$handler
     * @param array $methods
     * @param array $tokens
     */
    public function __construct($name, $pattern, $handler, array $methods = [], array $tokens= [])
    {
        $this->name = $name;
        $this->pattern = $pattern;
        $this->handler = $handler;
        $this->tokens = $tokens;
        $this->methods = $methods;
    }

}