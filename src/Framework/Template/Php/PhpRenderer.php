<?php

namespace Framework\Template\Php;

use Framework\Template\TemplateRendererInterface;

class PhpRenderer implements TemplateRendererInterface
{
    private $path;
    private $blocks = [];
    private $extends;
    private $blockNames;
    private $extensions = [];

    /**
     * PhpRenderer constructor.
     *
     * @param $path
     */
    public function __construct($path)
    {
        $this->path = $path;
        $this->blockNames = new \SplStack();
    }

    /**
     * @param Extension $extension
     */
    public function addExtension(Extension $extension)
    {
        $this->extensions[] = $extension;
    }

    /**
     * @param $name
     * @param array $params
     *
     * @return false|string
     *
     * @throws \Throwable|\Exception
     */
    public function render($name, array $params = []): string
    {
        $obLevel = ob_get_level();
        try {
            $templateFile = $this->path.'/'.$name.'.php';
            ob_start();
            extract($params, EXTR_SKIP);
            $this->extends = null;
            require $templateFile;
            $content = ob_get_clean();
            if (!$this->extends) {
                return $content;
            }
        } catch (\Throwable | \Exception $exception) {
            while (ob_get_level() > $obLevel) {
                ob_end_clean();
            }
            throw $exception;
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

    /**
     * @param string $name
     * @param array  $args
     *
     * @return mixed
     *
     * @throws \InvalidArgumentException
     */
    public function __call($name, array $args)
    {
        /** @var Extension $extension */
        foreach ($this->extensions as $extension) {
            $functions = $extension->getFunctions();
            /** @var SimpleFunction $function */
            foreach ($functions as $function) {
                if ($function->name === $name) {
                    if ($function->needRenderer) {
                        return ($function->callback)($this, ...$args);
                    }

                    return ($function->callback)(...$args);
                }
            }
        }
        throw new \InvalidArgumentException('Undefined function "'.$name.'"');
    }
}
