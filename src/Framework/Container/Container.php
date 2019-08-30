<?php

namespace Framework\Container;

/**
 * Class Container
 * @package Framework\Container
 */
class Container
{
    private $definitions = [];

    /**
     * Get parameters from container
     *
     * @param $id
     * @return mixed
     */
    public function get($id)
    {
        if (!array_key_exists($id, $this->definitions)) {
            throw new ServiceNotFoundException('Undefined parameter "' . $id . '"');
        }
        $definition = $this->definitions[$id];

        // Check if $definition is a instance of \Closure then run this function
        return $definition instanceof \Closure ? $definition() : $definition;
    }

    /**
     * Sets the parameters into Container
     *
     * @param $id
     * @param $value
     */
    public function set($id, $value)
    {
        $this->definitions[$id] = $value;
    }
}
