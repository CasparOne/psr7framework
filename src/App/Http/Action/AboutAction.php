<?php

namespace App\Http\Action;

use Framework\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\HtmlResponse;

/**
 * Class AboutAction.
 */
class AboutAction
{
    private $renderer;

    /**
     * AboutAction constructor.
     * @param TemplateRendererInterface $renderer
     */
    public function __construct(TemplateRendererInterface $renderer)
    {
        $this->renderer = $renderer;
    }

    /**
     * @return HtmlResponse
     */
    public function __invoke(): ResponseInterface
    {
        return new HtmlResponse($this->renderer->render('about'));
    }
}
