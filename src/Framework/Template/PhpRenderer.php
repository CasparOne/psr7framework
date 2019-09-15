<?php

namespace Framework\Template;

use Framework\Http\Router\RouterInterface;

class PhpRenderer implements TemplateRendererInterface
{
    private $path;
    private $blocks = [];
    private $extends;
    private $blockNames;
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * PhpRenderer constructor.
     *
     * @param $path
     * @param RouterInterface $router
     */
    public function __construct($path, RouterInterface $router)
    {
        $this->path = $path;
        $this->blockNames = new \SplStack();
        $this->router = $router;
    }

    /**
     * @param $view
     * @param array $params
     *
     * @return false|string
     */
    public function render($view, array $params = []): string
    {
        $templateFile = $this->path.'/'.$view.'.php';
        ob_start();
        extract($params, EXTR_SKIP);
        $this->extends = null;
        require $templateFile;
        $content = ob_get_clean();

        if (!$this->extends) {
            return $content;
        }

        return $this->render($this->extends);
    }

    /**
     * @param $view
     */
    public function extend($view)
    {
        $this->extends = $view;
    }

    public function block($name, $content)
    {
        if ($this->hasBlock($name)) {
            return;
        }
        $this->blocks[$name] = $content;
    }

    public function beginBlock($name): void
    {
        $this->blockNames->push($name);
        ob_start();
    }

    public function endBlock(): void
    {
        $content = ob_get_clean();
        $name = $this->blockNames->pop();
        if ($this->hasBlock($name)) {
            return;
        }
        $this->blocks[$name] = $content;
    }

    public function renderBlock($name)
    {
        $block = $this->blocks[$name] ?? null;
        if ($block instanceof \Closure) {
            return $block();
        }

        return $block ?? '';
    }

    public function hasBlock($name)
    {
        return array_key_exists($name, $this->blocks);
    }

    public function ensureBlock($name)
    {
        if ($this->hasBlock($name)) {
            return false;
        }
        $this->beginBlock($name);

        return true;
    }

    public function encode($string): string
    {
        return htmlspecialchars($string, ENT_QUOTES | ENT_SUBSTITUTE);
    }

    public function path($name, array $params = []): string
    {
        return $this->router->generate($name, $params);
    }
}
