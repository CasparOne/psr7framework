<?php

namespace Framework\Http\Router;

use Psr\Http\Message\ServerRequestInterface;
use Framework\Http\Router\Exception\RequestNotMatchedException;

class Router
{
    private $routes;

    public function __construct(RouteCollection $routes)
    {
        $this->routes = $routes;
    }

    public function match(ServerRequestInterface $request) : Result
    {
        foreach ($this->routes->getRoutes() as $route) {
            // Проверяет содержиться ли переданный метод в коллекции,
            // если true - текуща иттерация цикла прерываетсЯ
            if ($route->methods && !\in_array($request->getMethod(), $route->methods, true)) {
                continue;
            }
            $pattern = preg_replace_callback('~\{([^\}]+)\}~', function ($matches) {
                $argument = $matches[1];
                $replace = $route->tokens[$argument] ?? '[^}]+';
                return '(?P<' . $argument . '>' . $replace . ')';
            }, $route->pattern);

            if (preg_match($pattern, $request->getUri()->getPath(), $matches)) {
                return new Result(
                    $route->name,
                    $route->handler,
                    // Фильтрруем массив от мусора (выбор только те эелемнты, значение ключа которого является строкой
                    array_filter($matches, '\is_string', ARRAY_FILTER_USE_KEY));
            }
        }
        throw new RequestNotMatchedException($request);
    }

    public function generate($name, array $params =[]) : string
    {
        $arguments = array_filter($params);

        foreach ($this->routes->getRoutes() as $route) {
            if ($name !== $route->name) {
                continue;
            }
            // $url = ...

            if ($url !== null) { // TODO Fix it
                return $url;
            }

            throw new RouteNotFoundException($name, $params);
        }
    }

}