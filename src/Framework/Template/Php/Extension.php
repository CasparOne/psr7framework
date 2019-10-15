<?php

namespace Framework\Template\Php;

abstract class Extension
{
    /**
     * @return array
     */
    public function getFunctions(): array
    {
        return [];
    }
}
