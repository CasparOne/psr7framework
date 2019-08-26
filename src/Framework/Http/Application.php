<?php

namespace Framework\Http;

use Framework\Http\Pipeline\MiddlewareResolver;
use Framework\Http\Pipeline\Pipeline;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class Application
 * @package Framework\Http
 */
class Application extends Pipeline
{
    private $resolver;
    private $defaultHandler;

    /**
     * Application constructor.
     * @param MiddlewareResolver $resolver
     * @param callable $defaultHandler
     */
    public function __construct(MiddlewareResolver $resolver, callable $defaultHandler)
    {
        parent::__construct();
        $this->resolver = $resolver;
        $this->defaultHandler = $defaultHandler;
    }

    /**
     * @param callable $middleware
     */
    public function pipe($middleware): void
    {
        parent::pipe($this->resolver->resolve($middleware));
    }

    public function run(ServerRequestInterface $request)
    {
        return $this($request, $this->defaultHandler);
    }



}