<?php

namespace App\Http\Action;

use App\Http\Middleware\BasicAuthMiddleware;
use Framework\Template\TemplateRenderer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;

/**
 * Class CabinetAction.
 */
class CabinetAction
{
    private $renderer;

    /**
     * CabinetAction constructor.
     * @param TemplateRenderer $renderer
     */
    public function __construct(TemplateRenderer $renderer)
    {
        $this->renderer = $renderer;
    }

    /**
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $username = $request->getAttribute(BasicAuthMiddleware::ATTRIVUTE);


        return new HtmlResponse($this->renderer->render('cabinet', ['username' => $username]));
    }
}
