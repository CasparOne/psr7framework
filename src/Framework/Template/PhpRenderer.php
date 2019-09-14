<?php

namespace Framework\Template;

class PhpRenderer implements TemplateRendererInterface
{
    private $path;
    private $blocks = [];
    private $extends;
    private $blockNames;

    public function __construct($path)
    {
        $this->path = $path;
        $this->blockNames = new \SplStack();
    }

    /**
     * @param $view
     * @param array $params
     *
     * @return false|string
     */
    public function render($view, array $params = []): string
    {
        $templateFile = $this->path . '/' . $view . '.php';
        ob_start();
        extract($params, EXTR_SKIP);
        $this->extends = null;
        require $templateFile;
        $content = ob_get_clean();

        if (null === $this->extends) {
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
        $block = $this->blocks['name'] ?? null;
        if ($block instanceof \Closure) {
            return $block;
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
}
