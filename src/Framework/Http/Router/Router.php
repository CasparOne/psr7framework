<?php

namespace Framework\Http\Router;

use Framework\Http\Router\Exception\RouteNotFoundException;
use InvalidArgumentException;
use Psr\Http\Message\ServerRequestInterface;
use Framework\Http\Router\Exception\RequestNotMatchedException;
use function in_array;

class Router
{
    private $routes;

    public function __construct(RouteCollection $routes)
    {
        $this->routes = $routes;
    }
    public function match(ServerRequestInterface $request): Result
    {
        foreach ($this->routes->getRoutes() as $route) {
            // Проверяет содержиться ли переданный метод в коллекции,
            // если true - текуща иттерация цикла прерываетсЯ
            if ($route->methods && !in_array($request->getMethod(), $route->methods, true)) {
                continue;
            }
            $pattern = preg_replace_callback('~\{([^\}]+)\}~', function ($matches) use ($route) {
                $argument = $matches[1];
                $replace = $route->tokens[$argument] ?? '[^}]+';
                return '(?P<' . $argument . '>' . $replace . ')';
            }, $route->pattern);
            $path = $request->getUri()->getPath();
            if (preg_match('~^' . $pattern . '$~i', $path, $matches)) {
                return new Result(
                    $route->name,
                    $route->handler,
                    array_filter($matches, '\is_string', ARRAY_FILTER_USE_KEY)
                );
            }
        }
        throw new RequestNotMatchedException($request);
    }

    public function generate($name, array $params = []): string
    {
        $arguments = array_filter($params);
        foreach ($this->routes->getRoutes() as $route) {
            if ($name !== $route->name) {
                continue;
            }
            $url = preg_replace_callback('~\{([^\}]+)\}~', function ($matches) use (&$arguments) {
                $argument = $matches[1];
                if (!array_key_exists($argument, $arguments)) {
                    throw new InvalidArgumentException('Missing parameter "' . $argument . '"');
                }
                return $arguments[$argument];
            }, $route->pattern);
            if ($url !== null) {
                return $url;
            }
        }
        throw new RouteNotFoundException($name, $params);
    }

}