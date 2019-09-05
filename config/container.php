<?php

use Framework\Container\Container;

//##############################################
// Container initialization
//##############################################
$container = new Container(require __DIR__.'/dependencies.php');
$container->set('config', require __DIR__.'/../config/parameters.php');

return $container;
