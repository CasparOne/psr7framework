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
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request) : ResponseInterface
    {
        if ($this->queue->isEmpty()) {
            return ($this->default)($request);
        };

        $middleware = $this->queue->dequeue();

        return $middleware($request, function (ServerRequestInterface $request) {
            return $this($request);
        });
    }
}