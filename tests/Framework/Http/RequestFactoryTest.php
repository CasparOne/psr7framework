<?php


namespace Tests\Framework\Http;


use Framework\Http\Request;
use Framework\Http\RequestFactory;
use PHPUnit\Framework\TestCase;

class RequestFactoryTest extends TestCase
{
    public function testempty() : void
    {
        $request = RequestFactory::fromGlobals($queryParams = ['name' => 'John'],
            $parsedBody = ['age' => 22]);

        self::assertInstanceOf(Request::class, $request);
        self::assertEquals($queryParams, $request->getQueryParams());
        self::assertEquals($parsedBody, $request->getParsedBody());
    }

}