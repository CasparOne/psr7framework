<?php

namespace Framework\Http\Router\Route;

use Framework\Http\Router\Result;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Interface RouteInterface
 * @package Framework\Http\Router\Route
 */
interface RouteInterface
{
    /**
     * @param ServerRequestInterface $request
     * @return Result|null
     */
    public function match(ServerRequestInterface $request) : ?Result;

    /**
     * @param $name
     * @param array $params
     * @return string|null
     */
    public function generate($name, array $params = []) : ?string;
}