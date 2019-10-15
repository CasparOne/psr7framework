<?php

namespace Framework\Template;

interface TemplateRendererInterface
{
    /**
     * @param $name
     * @param array $params
     */
    public function render(string $name, array $params = []);
}
