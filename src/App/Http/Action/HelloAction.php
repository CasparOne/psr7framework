<?php

namespace App\Http\Action;

use Framework\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;

/**
 * Class HelloAction.
 */
class HelloAction
{
    private $renderer;

    /**
     * HelloAction constructor.
     * @param TemplateRendererInterface $renderer
     */
    public function __construct(TemplateRendererInterface $renderer)
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
        $name = $request->getQueryParams()['name'] ?? 'Guest';
        return new HtmlResponse($this->renderer->render('app/hello', ['name' => $name]));
    }
}
