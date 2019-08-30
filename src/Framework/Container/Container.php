<?php

namespace Framework\Container;

/**
 * Class Container
 * @package Framework\Container
 */
class Container
{
    private $definitions = [];
    private $results = [];

    /**
     * Get parameters from container
     *
     * @param $id
     * @return mixed
     */
    public function get($id)
    {
        if (array_key_exists($id, $this->results)) {
            return $this->results[$id];
        }
        if (!array_key_exists($id, $this->definitions)) {
            throw new ServiceNotFoundException('Undefined parameter "' . $id . '"');
        }
        $definition = $this->definitions[$id];
        // Check if $definition is a instance of \Closure
        $this->results[$id] = $definition instanceof \Closure ? $definition() : $definition;
        return $this->results[$id];
    }

    /**
     * Sets the parameters into Container
     *
     * @param $id
     * @param $value
     */
    public function set($id, $value)
    {
        if (array_key_exists($id, $this->results)) {
            unset($this->results[$id]);
        }
        $this->definitions[$id] = $value;
    }
}
