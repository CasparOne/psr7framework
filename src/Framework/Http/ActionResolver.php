<?php


namespace Framework\Http;


use function is_string;

class ActionResolver
{
    public function resolve($handler) : callable
    {
        return is_string($handler) ? new $handler : $handler;
    }

}