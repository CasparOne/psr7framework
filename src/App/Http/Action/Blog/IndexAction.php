<?php


namespace App\Http\Action\Blog;


use Zend\Diactoros\Response\JsonResponse;

class IndexAction
{
    public function __invoke()
    {
        return new JsonResponse([
            ['id' => 3, 'Title' =>'The third Post'],
            ['id' => 2, 'Title' =>'The Second Post'],
            ['id' => 1, 'Title' =>'The first Post'],
        ]);
    }

}