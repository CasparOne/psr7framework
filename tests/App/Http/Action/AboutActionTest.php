<?php


namespace Tests\App\Http\Action;

use App\Http\Action\AboutAction;
use PHPUnit\Framework\TestCase;
use Zend\Diactoros\ServerRequest;

class AboutActionTest extends TestCase
{
    public function testAbout()
    {
        $action = new AboutAction();
        $request = new ServerRequest();

        $response = $action($request);

        self::assertEquals(200, $response->getStatusCode());
        self::assertEquals('I am a Site. It\'s about section.', $response->getBody()->getContents());
    }
}
