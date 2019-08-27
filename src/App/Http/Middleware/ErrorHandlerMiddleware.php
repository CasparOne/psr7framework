<?php


namespace App\Http\Middleware;


use Psr\Http\Message\ServerRequestInterface;
use Throwable;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\JsonResponse;

class ErrorHandlerMiddleware
{
    private $debug;

    public function __construct(bool $debug = false)
    {
        $this->debug = $debug;
    }

    public function __invoke(ServerRequestInterface $request, callable $next)
    {
        try {
            return $next($request);
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