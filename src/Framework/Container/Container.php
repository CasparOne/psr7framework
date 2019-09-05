<?php

namespace Framework\Container;

/**
 * Class Container.
 */
class Container implements \ArrayAccess
{
    private $definitions = [];
    private $results = [];

    /**
     * Get parameters from container with auto wiring.
     *
     * @param $id
     *
     * @return mixed
     *
     * @throws \ReflectionException
     */
    public function get($id)
    {
        if (array_key_exists($id, $this->results)) {
            return $this->results[$id];
        }
        if (!array_key_exists($id, $this->definitions)) {
            if (class_exists($id)) {
                $reflection = new \ReflectionClass($id);
                $arguments = [];
                if (null !== ($constructor = $reflection->getConstructor())) {
                    foreach ($constructor->getParameters() as $param) {
                        if ($paramClass = $param->getClass()) {
                            $arguments[] = $this->get($paramClass->getName());
                        } elseif ($param->isArray()) {
                            $arguments[] = [];
                        } else {
                            if (!$param->isDefaultValueAvailable()) {
                                throw new ServiceNotFoundException('Unable to resolve "'.
                                $param->getName().'" in service '.$id);
                            }
                            $arguments[] = $param->getDefaultValue();
                        }
                    }
                }
                $this->results[$id] = $reflection->newInstanceArgs($arguments);

                return $this->results[$id];
            }
            throw new ServiceNotFoundException('Undefined parameter "'.$id.'"');
        }
        $definition = $this->definitions[$id];
        // Check if $definition is a instance of \Closure
        $this->results[$id] = $definition instanceof \Closure ? $definition($this) : $definition;

        return $this->results[$id];
    }

    /**
     * Sets the parameters into Container.
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

    /**
     * Whether a offset exists.
     *
     * @see https://php.net/manual/en/arrayaccess.offsetexists.php
     *
     * @param mixed $offset <p>
     *                      An offset to check for.
     *                      </p>
     *
     * @return bool true on success or false on failure.
     *              </p>
     *              <p>
     *              The return value will be casted to boolean if non-boolean was returned.
     *
     * @since 5.0.0
     */
    public function offsetExists($offset): bool
    {
        return array_key_exists($offset, $this->definitions);
    }

    /**
     * Offset to retrieve.
     *
     * @see https://php.net/manual/en/arrayaccess.offsetget.php
     *
     * @param mixed $offset <p>
     *                      The offset to retrieve.
     *                      </p>
     *
     * @return mixed can return all value types
     *
     * @since 5.0.0
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * Offset to set.
     *
     * @see https://php.net/manual/en/arrayaccess.offsetset.php
     *
     * @param mixed $offset <p>
     *                      The offset to assign the value to.
     *                      </p>
     * @param mixed $value  <p>
     *                      The value to set.
     *                      </p>
     *
     * @since 5.0.0
     */
    public function offsetSet($offset, $value): void
    {
        $this->set($offset, $value);
    }

    /**
     * Offset to unset.
     *
     * @see https://php.net/manual/en/arrayaccess.offsetunset.php
     *
     * @param mixed $offset <p>
     *                      The offset to unset.
     *                      </p>
     *
     * @since 5.0.0
     */
    public function offsetUnset($offset): void
    {
        unset($this->definitions[$offset]);
    }

    /**
     * @param $id
     *
     * @return bool
     */
    public function has($id): bool
    {
        return key_exists($id, $this->definitions) || class_exists($id);
    }
}
