<?php
chdir(dirname(__DIR__));

use Framework\Http\RequestFactory;

require 'vendor/autoload.php';
### Init
$request = RequestFactory::fromGlobals();


### Action
$name = $request->getQueryParams()['name'] ?? 'Guest';

$response = (new \Framework\Http\Response('Hello, ' . $name . '!'))
    ->withHeader('X-Developer', 'CasparOne');

### Send
header('HTTP/1.0' . $response->getStatusCode() . ' ' . $response->getReasonPhrase());
foreach ($response->getHeaders() as $header => $value) {
    header($header. ':' . $value);
}

echo $response->getBody();