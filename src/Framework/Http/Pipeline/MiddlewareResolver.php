<?php

namespace Framework\Http\Pipeline;

use Framework\Container\ContainerInterface;
use Interop\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Stratigility\MiddlewarePipe;

/**
 * Class MiddlewareResolver.
 */
class MiddlewareResolver
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * MiddlewareResolver constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param $handler
     * @param ResponseInterface $responsePrototype
     *
     * @return callable
     *
     * @throws \ReflectionException
     */
    public function resolve($handler, ResponseInterface $responsePrototype): callable
    {
        if (\is_array($handler)) {
            return $this->createPipe($handler, $responsePrototype);
        }

        if (\is_string($handler) && $this->container->has($handler)) {
            return function (ServerRequestInterface $request, ResponseInterface $response, callable $next) use ($handler) {
                $middleware = $this->resolve($this->container->get($handler), $response);

                return $middleware($request, $response, $next);
            };
        }

        if ($handler instanceof MiddlewareInterface) {
            return function (ServerRequestInterface $request, ResponseInterface $response, callable $next) use ($handler) {
                return $handler->process($request, new PsrHandlerWrapper($next));
            };
        }

        if (\is_object($handler)) {
            $reflection = new \ReflectionObject($handler);
            if ($reflection->hasMethod('__invoke')) {
                $method = $reflection->getMethod('__invoke');
                $parameters = $method->getParameters();
                if (2 === \count($parameters) && $parameters[1]->isCallable()) {
                    return function (ServerRequestInterface $request, ResponseInterface $response, callable $next) use ($handler) {
                        return $handler($request, $next);
                    };
                }

                return $handler;
            }
        }

        throw new UnknownMiddlewareTypeException($handler);
    }

    /**
     * @param array $handlers
     * @param mixed $responsePrototype
     *
     * @return MiddlewarePipe
     *
     * @throws \ReflectionException
     */
    protected function createPipe(array $handlers, $responsePrototype): MiddlewarePipe
    {
        $pipeline = new MiddlewarePipe();
        $pipeline->setResponsePrototype($responsePrototype);
        foreach ($handlers as $handler) {
            $pipeline->pipe($this->resolve($handler, $responsePrototype));
        }

        return $pipeline;
    }
}
