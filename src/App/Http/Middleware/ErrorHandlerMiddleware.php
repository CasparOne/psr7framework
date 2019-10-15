<?php

namespace App\Http\Middleware;

use Framework\Template\TemplateRendererInterface;
use Interop\Http\Server\MiddlewareInterface;
use Interop\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;
use Zend\Diactoros\Response\HtmlResponse;

class ErrorHandlerMiddleware implements MiddlewareInterface
{
    private $debug;
    private $template;

    public function __construct(bool $debug = false, TemplateRendererInterface $template)
    {
        $this->debug = $debug;
        $this->template = $template;
    }

    /**
     * Process an incoming server request and return a response, optionally delegating
     * response creation to a handler.
     *
     * @param ServerRequestInterface  $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (Throwable $exception) {
            $view = $this->debug ? 'error/error-debug' : 'error/error';

            return new HtmlResponse($this->template->render(
                $view, [
                    'request' => $request,
                    'exception' => $exception,
                ]
            ), $exception->getCode() ?: 500);
        }
    }
}
