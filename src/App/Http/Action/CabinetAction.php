<?php

namespace App\Http\Action;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;

/**
 * Class CabinetAction
 * @package App\Http\Action
 */
class CabinetAction
{
    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request) : ResponseInterface
    {
        $username = $request->getAttribute('username');
        return new HtmlResponse('I am logged in as ' . ucfirst($username));
    }
}