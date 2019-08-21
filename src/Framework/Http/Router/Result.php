<?php


namespace Framework\Http\Router;

/**
 * Class Result
 * @package Framework\Http\Router
 */
class Result
{
    private $name;
    private $handler;
    private $attributes;

    /**
     * Result constructor.
     * @param string $name
     * @param mixed $handler
     * @param array $attributes
     */
    public function __construct($name, $handler, array $attributes)
    {
        $this->name = $name;
        $this->handler = $handler;
        $this->attributes = $attributes;
    }

    /**
     * @return string
     */
    public function getName() : string
    {
        return (string) $this->name;
    }

    /**
     * @return mixed
     */
    public function getHandler()
    {
        return $this->handler;
    }

    /**
     * @return array
     */
    public function getAttributes() : array
    {
        return $this->attributes;
    }
}
