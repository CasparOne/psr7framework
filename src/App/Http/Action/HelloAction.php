<?php

namespace App\Http\Action;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;

/**
 * Class HelloAction
 * @package App\Http\Action
 */
class HelloAction
{
    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request) : ResponseInterface
    {
        $name = $request->getQueryParams()['name'] ?? 'Guest';

        return new HtmlResponse('Hello, ' . $name . '!');
    }
}
