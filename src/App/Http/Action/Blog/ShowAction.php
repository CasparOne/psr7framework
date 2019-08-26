<?php

namespace App\Http\Action\Blog;

use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

/**
 * Class ShowAction
 * @package App\Http\Action\Blog
 */
class ShowAction
{
    /**
     * @param ServerRequestInterface $request
     * @param callable $next
     * @return JsonResponse
     */
    public function __invoke(ServerRequestInterface $request, callable $next)
    {
        $id = $request->getAttribute('id');
        if ($id > 5) {
            return $next($request);
        }

        return new JsonResponse(['id' => $id, 'title' => 'Post # ' . $id]);
    }
}