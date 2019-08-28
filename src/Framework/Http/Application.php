<?php

namespace Framework\Http;

use Framework\Http\Pipeline\MiddlewareResolver;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Stratigility\MiddlewarePipe;

/**
 * Class Application
 * @package Framework\Http
 */
class Application extends MiddlewarePipe
{
    private $resolver;
    private $defaultHandler;

    /**
     * Application constructor.
     * @param MiddlewareResolver $resolver
     * @param callable $defaultHandler
     * @param ResponseInterface $responsePrototype
     */
    public function __construct(MiddlewareResolver $resolver, callable $defaultHandler, ResponseInterface $responsePrototype)
    {
        parent::__construct();
        $this->resolver = $resolver;
        $this->setResponsePrototype($responsePrototype);
        $this->defaultHandler = $defaultHandler;
    }

    /**
     * @param $path
     * @param mixed $middleware
     * @return MiddlewarePipe
     * @throws \ReflectionException
     */
    public function pipe($path, $middleware = null) : MiddlewarePipe
    {
        if ($middleware === null) {
            return parent::pipe($this->resolver->resolve($path, $this->responsePrototype));
        }
        return parent::pipe($path, $this->resolver->resolve($middleware, $this->responsePrototype));
    }

    public function run(ServerRequestInterface $request, ResponseInterface $response) : ResponseInterface
    {
        return $this($request, $response, $this->defaultHandler);
    }



}