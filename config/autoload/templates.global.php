<?php

return [
    'dependencies' => [
        'factories' => [
            Framework\Template\TemplateRendererInterface::class => function (Psr\Container\ContainerInterface $c) {
                $renderer = new \Framework\Template\Twig\TwigRenderer($c->get(\Twig\Environment::class),
                    $c->get('config')['templates']['extension']);

                return $renderer;
            },
            \Twig\Environment::class => function (\Psr\Container\ContainerInterface $c) {
                $config = $c->get('config')['twig'];
                $debug = $c->get('config')['debug'];

                $loader = new \Twig\Loader\FilesystemLoader();
                $loader->addPath($config['view']);

                $environment = new \Twig\Environment($loader, [
                    'cache' => $debug ? false : $config['cache'],
                    'debug' => $debug,
                    'strict_variables' => $debug,
                    'auto_reload' => $debug,
                ]);
                if ($debug) {
                    $environment->addExtension(new \Twig\Extension\DebugExtension());
                }
                $environment->addExtension($c->get(\Framework\Template\Twig\Extension\RouteExtension::class));

                foreach ($config['extensions'] as $extension) {
                    $environment->addExtension($c->get($extension));
                }

                return $environment;
            },
        ],
    ],
    'templates' => [
        'extension' => '.html.twig',
    ],
    'twig' => [
        'view' => 'templates',
        'cache' => 'var/cache/twig',
        'extensions' => [],
    ],
];
