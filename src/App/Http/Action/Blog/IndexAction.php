<?php

namespace App\Http\Action\Blog;

use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\JsonResponse;

/**
 * Class IndexAction
 * @package App\Http\Action\Blog
 */
class IndexAction
{
    /**
     * @return JsonResponse
     */
    public function __invoke() : ResponseInterface
    {
        return new JsonResponse([
            ['id' => 3, 'Title' =>'The third Post'],
            ['id' => 2, 'Title' =>'The Second Post'],
            ['id' => 1, 'Title' =>'The first Post'],
        ]);
    }
}
