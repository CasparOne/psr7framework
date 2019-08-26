<?php

namespace App\Http\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\EmptyResponse;

/**
 * Class BasicAuthMiddleware
 * @package App\Http\Middleware
 */
class BasicAuthMiddleware
{
    public const ATTRIVUTE = '_user';

    private $users;

    /**
     * BasicAuthMiddleware constructor.
     * @param array $users
     */
    public function __construct(array $users)
    {
        $this->users = $users;
    }

    /**
     * @param ServerRequestInterface $request
     * @param callable $next
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, callable $next) : ResponseInterface
    {
        $username = $request->getServerParams()['PHP_AUTH_USER'] ?? null;
        $password = $request->getServerParams()['PHP_AUTH_PW'] ?? null;
        if (!empty($username) && !empty($password)) {
            foreach ($this->users as $user => $pass) {
                if ($username === $user && $password === $pass) {
                    return $next($request->withAttribute(self::ATTRIVUTE, $username));
                }
            }
        }
        return new EmptyResponse(401, ['WWW-Authenticate' => 'Basic realm=Manager panel']);
    }

}