<?php

use Framework\Http\Router\RouterInterface;

return [
    'dependencies' => [
        'abstract_factories' => [
            \Zend\ServiceManager\AbstractFactory\ReflectionBasedAbstractFactory::class,
        ],
        'factories' => [
            Framework\Http\Application::class => function (Psr\Container\ContainerInterface $c) {
                return new Framework\Http\Application(
                    $c->get(Framework\Http\Pipeline\MiddlewareResolver::class),
                    $c->get(Framework\Http\Router\RouterInterface::class),
                    $c->get(App\Http\Middleware\NotFoundHandler::class),
                    new Zend\Diactoros\Response()
                );
            },
            App\Http\Middleware\ErrorHandlerMiddleware::class => function (Psr\Container\ContainerInterface $c) {
                return new App\Http\Middleware\ErrorHandlerMiddleware($c->get('config')['debug']);
            },
            Framework\Http\Pipeline\MiddlewareResolver::class => function (Psr\Container\ContainerInterface $c) {
                return new Framework\Http\Pipeline\MiddlewareResolver($c);
            },
            Framework\Http\Router\RouterInterface::class => function () {
                return new Framework\Http\Router\AuraRouterAdapter(new Aura\Router\RouterContainer());
            },
            Framework\Template\TemplateRendererInterface::class => function (Psr\Container\ContainerInterface $c) {
                return new Framework\Template\PhpRenderer($c->get('config')['view'], $c->get(RouterInterface::class));
            },
        ],
    ],
    'debug' => false,
];
