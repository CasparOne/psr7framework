<?php

namespace Framework\Http\Router\Exception;

use Throwable;

/**
 * Class RouteNotFoundException
 * @package Framework\Http\Router\Exception
 */
class RouteNotFoundException extends \LogicException
{
    private $name;
    private $params;

    /**
     * RouteNotFoundException constructor.
     * @param $name
     * @param array $params
     */
    public function __construct($name, array $params)
    {
        parent::__construct('Route "' . $name . '" not found');
        $this->name = $name;
        $this->params = $params;
    }

    /**
     * @return string
     */
    public function getName() : string
    {
        return  $this->name;
    }

    /**
     * @return array
     */
    public function getParams() : array
    {
        return $this->params;
    }

}
