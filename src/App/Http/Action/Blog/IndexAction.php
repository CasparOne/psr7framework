<?php

namespace App\Http\Action\Blog;

use App\ReadModel\PostReadRepository;
use Framework\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\HtmlResponse;

/**
 * Class IndexAction.
 */
class IndexAction
{
    private $posts;
    private $template;

    public function __construct(PostReadRepository $posts, TemplateRendererInterface $template)
    {
        $this->posts = $posts;
        $this->template = $template;
    }

    /**
     * @return HtmlResponse
     */
    public function __invoke(): ResponseInterface
    {
        $posts = $this->posts->getAll();
        return new HtmlResponse($this->template->render('app/blog/index', [
            'posts' => $posts,
            ]));
    }
}
