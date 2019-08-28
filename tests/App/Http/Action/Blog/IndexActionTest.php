<?php
namespace Tests\App\Http\Action\Blog;

use App\Http\Action\Blog\IndexAction;
use PHPUnit\Framework\TestCase;

class IndexActionTest extends TestCase
{
    public function testSuccess()
    {
        $action = new IndexAction();
        $response = $action();
        self::assertEquals(200, $response->getStatusCode());
        self::assertJsonStringEqualsJsonString(
            json_encode([
                ['id' => 3, 'Title' =>'The third Post'],
                ['id' => 2, 'Title' =>'The Second Post'],
                ['id' => 1, 'Title' =>'The first Post'],
            ]),
            $response->getBody()->getContents()
        );
    }
}
