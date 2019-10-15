<?php

namespace Framework\Template\Php\Extension;

use Framework\Http\Router\RouterInterface;
use Framework\Template\Php\Extension;
use Framework\Template\Php\SimpleFunction;

class RouteExtension extends Extension
{
    private $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @return array
     */
    public function getFunctions(): array
    {
        return [
            new SimpleFunction('path', [$this, 'generatePath']),
        ];
    }

    /**
     * @param $name
     * @param array $params
     *
     * @return string
     */
    public function generatePath($name, array $params = []): string
    {
        return $this->router->generate($name, $params);
    }
}
