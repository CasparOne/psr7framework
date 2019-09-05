<?php

chdir(dirname(__DIR__));

use Framework\Http\Application;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequestFactory;
use Zend\HttpHandlerRunner\Emitter\SapiEmitter;

require 'vendor/autoload.php';

$container = require __DIR__.'/../config/container.php';

/** @var Application $app */
$app = $container->get(Application::class);

require __DIR__.'/../config/pipeline.php';
require __DIR__.'/../config/routes.php';

$request = ServerRequestFactory::fromGlobals();
$response = $app->run($request, new Response());

$emitter = new SapiEmitter();
$emitter->emit($response);
