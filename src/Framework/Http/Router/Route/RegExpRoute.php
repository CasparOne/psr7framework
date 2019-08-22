<?php

namespace Framework\Http\Router\Route;

use Framework\Http\Router\Result;
use InvalidArgumentException;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class RegExpRoute
 * @package Framework\Http\Router\Route
 */
class RegExpRoute implements RouteInterface
{
    public $name;
    public $pattern;
    public $handler;
    public $tokens;
    public $methods;

    /**
     * RegExpRoute constructor.
     * @param string $name
     * @param string $pattern
     * @param $handler
     * @param array $methods
     * @param array $tokens
     */
    public function __construct(string $name, string $pattern, $handler, array $methods = [], array $tokens= [])
    {
        $this->name = $name;
        $this->pattern = $pattern;
        $this->handler = $handler;
        $this->tokens = $tokens;
        $this->methods = $methods;
    }

    /**
     * @param ServerRequestInterface $request
     * @return Result|null
     */
    public function match(ServerRequestInterface $request) : ?Result
    {
        if ($this->methods && !in_array($request->getMethod(), $this->methods, true)) {
            return null;
        }
        $pattern = preg_replace_callback('~\{([^\}]+)\}~', function ($matches){
            $argument = $matches[1];
            $replace = $route->tokens[$argument] ?? '[^}]+';
            return '(?P<' . $argument . '>' . $replace . ')';
        }, $this->pattern);

        $path = $request->getUri()->getPath();
        if (preg_match('~^' . $pattern . '$~i', $path, $matches)) {
            return new Result(
                $this->name,
                $this->handler,
                array_filter($matches, '\is_string', ARRAY_FILTER_USE_KEY));}
        return null;
    }

    /**
     * @param $name
     * @param array $params
     * @return string|null
     */
    public function generate($name, array $params = []) : ?string
    {
        $arguments = array_filter($params);
        if ($name !== $this->name) {
            return null;
        }
        $url = preg_replace_callback('~\{([^\}]+)\}~', function ($matches) use (&$arguments) {
            $argument = $matches[1];
            if (!array_key_exists($argument, $arguments)) {
                throw new InvalidArgumentException('Missing parameter "' . $argument . '"');
            }
            return $arguments[$argument];
        }, $this->pattern);
        if ($url !== null) {
            return $url;
        }
        return null;
    }
}