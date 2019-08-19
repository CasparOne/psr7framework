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
    ->withHeader('X-Developer', 'CasparOne');

### Send

header('HTTP/1.0' . $response->getStatusCode() . ' ' . $response->getReasonPhrase());
foreach ($response->getHeaders() as $name => $value) {
    header($name. ':' . implode(', ', $value));
}

echo $response->getBody();