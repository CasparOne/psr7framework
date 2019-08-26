<?php

namespace Framework\Http;

use function is_string;

/**
 * Class ActionResolver
 * @package Framework\Http
 */
class ActionResolver
{
    /**
     * @param mixed $handler
     * @return callable
     */
    public function resolve($handler) : callable
    {
        return is_string($handler) ? new $handler : $handler;
    }
}