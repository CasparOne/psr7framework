<?php


namespace Tests\App\Http\Action\Blog;


use App\Http\Action\Blog\IndexAction;
use PHPUnit\Framework\TestCase;
use Zend\Diactoros\ServerRequest;

class IndexActionTest extends TestCase
{
    public function testStatus()
    {
        $action = new IndexAction();
        $request = new ServerRequest();

        $response = $action($request);

        self::assertEquals(200, $response->getStatusCode());
        self::assertJson($response->getBody()->getContents());
    }

}