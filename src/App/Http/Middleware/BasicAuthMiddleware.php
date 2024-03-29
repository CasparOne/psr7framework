<?php

namespace App\Http\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class BasicAuthMiddleware.
 */
class BasicAuthMiddleware
{
    public const ATTRIVUTE = '_user';

    private $users;

    /**
     * BasicAuthMiddleware constructor.
     *
     * @param array $users
     */
    public function __construct(array $users)
    {
        $this->users = $users;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param callable               $next
     *
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
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

        return $response
            ->withStatus(401)
            ->withHeader('WWW-Authenticate', 'Basic realm=Manager panel');
    }
}
