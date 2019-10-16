<?php

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
                return new App\Http\Middleware\ErrorHandlerMiddleware($c->get('config')['debug'],
                    $c->get(\Framework\Template\TemplateRendererInterface::class));
            },
            Framework\Http\Pipeline\MiddlewareResolver::class => function (Psr\Container\ContainerInterface $c) {
                return new Framework\Http\Pipeline\MiddlewareResolver($c);
            },
            Framework\Http\Router\RouterInterface::class => function () {
                return new Framework\Http\Router\AuraRouterAdapter(new Aura\Router\RouterContainer());
            },
            Framework\Template\TemplateRendererInterface::class => function (Psr\Container\ContainerInterface $c) {
                $renderer = new \Framework\Template\Twig\TwigRenderer($c->get(\Twig\Environment::class),
                    $c->get('config')['twig']['extension']);

                return $renderer;
            },
            \Twig\Environment::class => function (\Psr\Container\ContainerInterface $c) {
                $config = $c->get('config')['twig'];
                $debug = $c->get('config')['debug'];
                $templateDir = $config['view'];
                $cacheDir = $config['cache'];

                $loader = new \Twig\Loader\FilesystemLoader();
                $loader->addPath($templateDir);

                $environment = new \Twig\Environment($loader, [
                    'cache' => $debug ? false : $cacheDir,
                    'debug' => $debug,
                    'strict_variables' => $debug,
                    'auto_reload' => $debug,
                    ]);
                if ($debug) {
                    $environment->addExtension(new \Twig\Extension\DebugExtension());
                }

                $environment->addExtension($c->get(\Framework\Template\Twig\Extension\RouteExtension::class));

                return $environment;
            },
        ],
    ],
    'debug' => false,
    'twig' => [
        'view' => 'templates',
        'extension' => '.html.twig',
        'cache' => 'var/cache/twig',
    ],
];
