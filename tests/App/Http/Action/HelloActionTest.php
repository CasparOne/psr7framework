<?php


namespace Tests\App\Http\Action;

use App\Http\Action\HelloAction;
use Framework\Template\PhpRenderer;
use PHPUnit\Framework\TestCase;
use Zend\Diactoros\ServerRequest;

class HelloActionTest extends TestCase
{
    private $renderer;

    public function setUp()
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->renderer = new PhpRenderer('templates');
    }

    public function testGuest()
    {
        $action = new HelloAction($this->renderer);

        $request = new ServerRequest();
        $response = $action($request);

        self::assertEquals(200, $response->getStatusCode());
        self::assertContains('Hello, Guest!', $response->getBody()->getContents());
    }

    public function testJohn()
    {
        $action = new HelloAction($this->renderer);

        $request = new ServerRequest();
        $request = $request->withQueryParams(['name' => 'John']);

        $response = $action($request);

        self::assertEquals(200, $response->getStatusCode());
        self::assertContains('Hello, John!', $response->getBody()->getContents());
    }
}
