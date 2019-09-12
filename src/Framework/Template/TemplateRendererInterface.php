<?php


namespace Framework\Template;


interface TemplateRendererInterface
{
    /**
     * @param $view
     * @param array $params
     */
    public function render(string $view, array $params = []);

}