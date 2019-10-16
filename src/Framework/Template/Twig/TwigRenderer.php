<?php

namespace Framework\Template\Twig;

use Framework\Template\TemplateRendererInterface;
use Twig\Environment;

class TwigRenderer implements TemplateRendererInterface
{
    private $twig;
    private $extension;

    public function __construct(Environment $twig, $extension)
    {
        $this->twig = $twig;
        $this->extension = $extension;
    }

    /**
     * @param string $name
     * @param array  $params
     *
     * @return string
     *
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function render(string $name, array $params = []): string
    {
        return $this->twig->render($name.$this->extension, $params);
    }
}
