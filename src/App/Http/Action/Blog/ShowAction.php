<?php

namespace App\Http\Action\Blog;

use App\ReadModel\PostReadRepository;
use Framework\Template\TemplateRendererInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;

/**
 * Class ShowAction.
 */
class ShowAction
{
    private $posts;
    private $template;

    public function __construct(PostReadRepository $posts, TemplateRendererInterface $template)
    {
        $this->posts = $posts;
        $this->template = $template;
    }

    /**
     * @param ServerRequestInterface $request
     * @param callable               $next
     *
     * @return HtmlResponse
     */
    public function __invoke(ServerRequestInterface $request, callable $next)
    {
        if (!$post = $this->posts->find($id = $request->getAttribute('id'))) {
            return $next($request);
        }

        return new HtmlResponse($this->template->render('app/blog/show', [
            'post' => $post,
        ]));
    }
}
