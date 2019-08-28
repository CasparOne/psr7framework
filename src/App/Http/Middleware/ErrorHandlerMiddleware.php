<?php


namespace App\Http\Middleware;

use Interop\Http\Server\MiddlewareInterface;
use Interop\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\JsonResponse;

class ErrorHandlerMiddleware implements MiddlewareInterface
{
    private $debug;

    public function __construct(bool $debug = false)
    {
        $this->debug = $debug;
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
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (Throwable $exception) {
            if ($this->debug) {
                return new JsonResponse([
                    'error'     => 'Server Error',
                    'code'      => $exception->getCode(),
                    'message'   => $exception->getMessage(),
                    'trace'     => $exception->getTrace(),
                ], 500);
            }
            return new HtmlResponse('Server Error', 500);
        }
    }
}
