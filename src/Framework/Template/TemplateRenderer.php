<?php

namespace Framework\Template;

class TemplateRenderer
{
    private $path;

    public function __construct($path)
    {
        $this->path = $path;
    }

    /**
     * @param $view
     * @param array $params
     * @return false|string
     */
    public function render($view, array $params = []): string
    {
        $templateFile = $this->path . '/' . $view . '.php';
        extract($params, EXTR_SKIP);
        ob_start();
        require $templateFile;
        return ob_get_clean();
    }

}