<?php

namespace App\Http\Action;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\EmptyResponse;

/**
 * Class BasicAuthActionDecorator
 * @package App\Http\Action
 */
class BasicAuthActionDecorator
{
    private $next;
    private $users;

    /**
     * BasicAuthActionDecorator constructor.
     * @param callable $next
     * @param array $users
     */
    public function __construct(callable $next, array $users)
    {
        $this->next = $next;
        $this->users = $users;
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request) : ResponseInterface
    {
        $username = $request->getServerParams()['PHP_AUTH_USER'] ?? null;
        $password = $request->getServerParams()['PHP_AUTH_PW'] ?? null;
        if (!empty($username) && !empty($password)) {
            foreach ($this->users as $user => $pass) {
                if ($username === $user && $password === $pass) {
                    return ($this->next)($request->withAttribute('username', $username));
                }
            }
        }
        return new EmptyResponse(401, ['WWW-Authenticate' => 'Basic realm=Manager panel']);
    }

}