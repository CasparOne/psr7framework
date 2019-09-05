<?php

use Framework\Container\Container;

//##############################################
// Config
//##############################################
$container = new Container();
$container->set('config', require __DIR__.'/../config/parameters.php');

//###############################################
// Application Init
//###############################################
require __DIR__.'/../config/dependencies.php';

return $container;
