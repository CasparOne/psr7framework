<?php
chdir(dirname(__DIR__));


use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\ServerRequestFactory;


require 'vendor/autoload.php';
### Init
$request = ServerRequestFactory::fromGlobals();


### Action
$name = $request->getQueryParams()['name'] ?? 'Guest';

$response = (new HtmlResponse('Hello, ' . $name . '!'))
    ->withHeader('X-Developer', 'CasparOne')
    ;

### Send
$emmiter = new \Framework\Http\ResponseSender();
$emmiter->send($response);