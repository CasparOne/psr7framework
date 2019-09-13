<?php

return [
    'dependencies' => [
        'factories' => [
            App\Http\Middleware\BasicAuthMiddleware::class => function (Psr\Container\ContainerInterface $container) {
                return new App\Http\Middleware\BasicAuthMiddleware($container->get('config')['auth']['users']);
            },
        ],
    ],
    'auth' => [
        'users' => [],
    ],
];
