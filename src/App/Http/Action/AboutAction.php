<?php


namespace App\Http\Action;

use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\HtmlResponse;

/**
 * Class AboutAction
 * @package App\Http\Action
 */
class AboutAction
{
    /**
     * @return HtmlResponse
     */
    public function __invoke() : ResponseInterface
    {
        return new HtmlResponse('I am a Site. It\'s about section.');
    }
}
