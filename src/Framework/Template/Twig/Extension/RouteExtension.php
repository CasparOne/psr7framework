<?php

namespace Framework\Template\Twig\Extension;

use Framework\Http\Router\RouterInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class RouteExtension extends AbstractExtension
{
    private $router;

    /**
     * RouteExtension constructor.
     * @param RouterInterface $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @return array|TwigFunction[]
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('path', [$this, 'generatePath']),
        ];
    }

    /**
     * @param $name
     * @param array $params
     * @return string
     */
    public function generatePath($name, array $params = []): string
    {
        return $this->router->generate($name, $params);
    }
}
