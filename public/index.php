<?php
chdir(dirname(__DIR__));


use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\ServerRequestFactory;
use Zend\HttpHandlerRunner\Emitter\SapiEmitter;
use Zend\Diactoros\Response\JsonResponse;


require 'vendor/autoload.php';
### Initialization

$request = ServerRequestFactory::fromGlobals();

### Action
$path = $request->getUri()->getPath();

if ('/' === $path) {
    $action = function (ServerRequestInterface $request) {
        $name = $request->getQueryParams()['name'] ?? 'Guest';
        return new HtmlResponse('Hello, ' . $name . '!');
    };
} elseif ('/about' === $path) {
    $action = function (ServerRequestInterface $request) {
        $name = $request->getQueryParams()['name'] ?? 'Guest';
        return new HtmlResponse('It\'s a simple site and About section');
    };
} elseif ('/blog' === $path) {
    $action = function (ServerRequestInterface $request) {
        return new JsonResponse([
            ['id' => 2, 'title' => 'Second blog post'],
            ['id' => 1, 'title' => 'First blog post'],
        ]);
    };
} elseif (preg_match('#^/blog/(?P<id>\d+)$#i', $path, $matches)) {

    $request = $request->withAttribute('id', $matches['id']);
    $action = function (ServerRequestInterface $request) {
        $id = $request->getAttribute('id');
        if ($id >2) {
            $response = new JsonResponse(['error' => 'Undefined page'], 404);
        } else {
            $response = new JsonResponse(['id' => $id, 'title' => 'Post #' . $id]);
        }
        return $response;
    };
}

if (is_callable($action)) {
    $response = $action($request);
} else {
    $response = new JsonResponse(['error' => 'Undefined page'], 404);
}

### Postprocessing

$response = $response->withHeader('X-Developer', 'CasparOne');

### Sending

$emitter = new SapiEmitter();
$emitter->emit($response);