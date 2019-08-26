<?php

namespace Tests\Framework\Http;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Diactoros\ServerRequest;
use Framework\Http\Pipeline\Pipeline;

class PipelineTest extends TestCase
{
    public function testPipeline()
    {
        $pipeline = new Pipeline();
        $pipeline->pipe(new Middleware1());
        $pipeline->pipe(new Middleware2());

        $response = $pipeline(new ServerRequest(), new Last());
        $this->assertJsonStringEqualsJsonString(
            json_encode(['middleware-1' => 1,'middleware-2' => 2,]),
            $response->getBody()->getContents()
        );

    }
}

class Middleware1
{
    public function __invoke(ServerRequestInterface $request, callable $next)
    {
        return $next($request->withAttribute('middleware-1', 1));
    }
}

class Middleware2
{
    public function __invoke(ServerRequestInterface $request, callable $next)
    {
        return $next($request->withAttribute('middleware-2', 2));
    }
}

class Last
{
    public function __invoke(ServerRequestInterface $request, callable $next)
    {
        return new JsonResponse($request->getAttributes());
    }
}