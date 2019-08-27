<?php

namespace Framework\Http\Middleware;

use Framework\Http\Router\Exception\RequestNotMatchedException;
use Framework\Http\Router\Result;
use Framework\Http\Router\RouterInterface;
use Interop\Http\Server\MiddlewareInterface;
use Interop\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class RouteMiddleware
 * @package Framework\Http\Middleware
 */
class RouteMiddleware implements MiddlewareInterface
{
    private $router;

    /**
     * RouteMiddleware constructor.
     * @param RouterInterface $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * Process an incoming server request and return a response, optionally delegating
     * response creation to a handler.
     *
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler)
    {
        try {
            $result = $this->router->match($request);
            foreach ($result->getAttribute() as $attribute => $value) {
                $request = $request->withAttribute($attribute, $value);
            }
            $request = $request->withAttribute(Result::class, $result);
        } catch (RequestNotMatchedException $exception) {}
        return $handler->handle($request);
    }
}