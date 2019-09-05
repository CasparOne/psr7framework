<?php

namespace Framework\Container;

/**
 * Class Container.
 */
interface ContainerInterface
{
    /**
     * Get parameters from container with auto wiring.
     *
     * @param $id
     *
     * @return mixed
     *
     * @throws ServiceNotFoundExceptionInterface
     */
    public function get($id);

    /**
     * @param $id
     *
     * @return bool
     */
    public function has($id): bool;
}
