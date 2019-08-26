<?php

namespace App\Http\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;

/**
 * Class NotFoundHandler
 * @package App\Http\Middleware
 */
class NotFoundHandler
{
    /**
     * @param ServerRequestInterface $request
     * @return HtmlResponse
     */
    public function __invoke(ServerRequestInterface $request) : ResponseInterface
    {
        return new HtmlResponse('Undefined page', 404);
    }
}