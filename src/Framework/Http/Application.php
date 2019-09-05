<?php

namespace Framework\Http;

use Framework\Http\Pipeline\MiddlewareResolver;
use Framework\Http\Router\RouteData;
use Framework\Http\Router\RouterInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Stratigility\MiddlewarePipe;

/**
 * Class Application.
 */
class Application extends MiddlewarePipe
{
    private $resolver;
    private $defaultHandler;
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * Application constructor.
     *
     * @param MiddlewareResolver $resolver
     * @param RouterInterface    $router
     * @param callable           $defaultHandler
     * @param ResponseInterface  $responsePrototype
     */
    public function __construct(MiddlewareResolver $resolver, RouterInterface $router, callable $defaultHandler, ResponseInterface $responsePrototype)
    {
        parent::__construct();
        $this->resolver = $resolver;
        $this->setResponsePrototype($responsePrototype);
        $this->defaultHandler = $defaultHandler;
        $this->router = $router;
    }

    /**
     * @param $path
     * @param mixed $middleware
     *
     * @return MiddlewarePipe
     *
     * @throws \ReflectionException
     */
    public function pipe($path, $middleware = null): MiddlewarePipe
    {
        if (null === $middleware) {
            return parent::pipe($this->resolver->resolve($path, $this->responsePrototype));
        }

        return parent::pipe($path, $this->resolver->resolve($middleware, $this->responsePrototype));
    }

    public function run(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        return $this($request, $response, $this->defaultHandler);
    }

    public function route($name, $path, $handler, array $methods, array $options = []): void
    {
        $this->router->addRoute(new RouteData($name, $path, $handler, $methods, $options));
    }

    public function any($name, $path, $handler, array $options = []): void
    {
        $this->route($name, $path, $handler, [], $options);
    }

    public function get($name, $path, $handler, array $options = []): void
    {
        $this->route($name, $path, $handler, ['GET'], $options);
    }

    public function post($name, $path, $handler, array $options = []): void
    {
        $this->route($name, $path, $handler, ['POST'], $options);
    }
}
