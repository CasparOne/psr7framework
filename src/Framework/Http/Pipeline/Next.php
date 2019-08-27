<?php

namespace Framework\Http\Pipeline;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SplQueue;

/**
 * Class Next
 * @package Framework\Http\Pipeline
 */
class Next
{
    /** @var callable $default  */
    private $default;
    /** @var SplQueue $queue */
    private $queue;

    /**
     * Next constructor.
     * @param SplQueue $queue
     * @param callable $next
     */
    public function __construct(SplQueue $queue, callable $next)
    {
        $this->queue = $queue;
        $this->default = $next;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response) : ResponseInterface
    {
        if ($this->queue->isEmpty()) {
            return ($this->default)($request, $response);
        };

        $middleware = $this->queue->dequeue();

        return $middleware($request, $response, function (ServerRequestInterface $request) use ($response) {
            return $this($request, $response);
        });
    }
}