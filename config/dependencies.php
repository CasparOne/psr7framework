<?php

use App\Http\Action\AboutAction;
use App\Http\Action\Blog\IndexAction;
use App\Http\Action\Blog\ShowAction;
use App\Http\Action\CabinetAction;
use App\Http\Action\HelloAction;
use App\Http\Middleware\BasicAuthMiddleware;
use App\Http\Middleware\CredentialsMiddleware;
use App\Http\Middleware\ErrorHandlerMiddleware;
use App\Http\Middleware\NotFoundHandler;
use App\Http\Middleware\ProfilerMiddleware;
use Aura\Router\RouterContainer;
use Framework\Container\Container;
use Framework\Http\Application;
use Framework\Http\Middleware\DispatchMiddleware;
use Framework\Http\Middleware\RouteMiddleware;
use Framework\Http\Pipeline\MiddlewareResolver;
use Framework\Http\Router\AuraRouterAdapter;
use Framework\Http\Router\RouterInterface;
use Zend\Diactoros\Response;

$container->set(Application::class, function (Container $c) {
    return new Application(
        $c->get(MiddlewareResolver::class),
        $c->get(RouterInterface::class),
        new NotFoundHandler(),
        new Response()
    );
});

//###############################################
// Services
//###############################################
// Middleware
$container->set(BasicAuthMiddleware::class, function (Container $c) {
    return new BasicAuthMiddleware($c->get('config')['users']);
});
$container->set(ErrorHandlerMiddleware::class, function (Container $c) {
    return new ErrorHandlerMiddleware($c->get('config')['debug']);
});
$container->set(MiddlewareResolver::class, function (Container $c) {
    return new MiddlewareResolver($c);
});
$container->set(RouterInterface::class, function () {
    return new AuraRouterAdapter(new RouterContainer());
});

