<?php


namespace Tests\App\Http\Action\Blog;


use App\Http\Action\Blog\ShowAction;
use PHPUnit\Framework\TestCase;
use Zend\Diactoros\ServerRequest;

class ShowActionTest extends TestCase
{
    public function testBlogShow()
    {
        $action = new ShowAction();
        $request = new ServerRequest();
        $request = $request->withQueryParams(['id' => 1]);

        $response = $action($request);

        self::assertEquals(200, $response->getStatusCode());
        self::assertJson($response->getBody()->getContents());
    }

    public function testError()
    {
        $action = new ShowAction();
        $request = new ServerRequest();
        $request = $request->withQueryParams(['id' => '88']);

        $response = $action($request);

        self::assertEquals(200, $response->getStatusCode());
        self::assertJson($response->getBody()->getContents());
    }

}