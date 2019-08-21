<?php
chdir(dirname(__DIR__));

require 'vendor/autoload.php';

use Framework\Http\Router\Exception\RequestNotMatchedException as Exception;
use Framework\Http\Router\RouteCollection;
use Framework\Http\Router\Router;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Diactoros\ServerRequestFactory;
use Zend\HttpHandlerRunner\Emitter\SapiEmitter as Emitter;

### Initialization
$routes = new RouteCollection();

$routes->get('home', '/', function (ServerRequestInterface $request) {
    $name = $request->getQueryParams()['name'] ?? 'Guest';
    return new HtmlResponse('Hello, ' . $name . '!');
});
$routes->get('about', '/about', function (ServerRequestInterface $request) {
    return new HtmlResponse('It\'s a simple site and About section');
});
$routes->get('blog', '/blog', function (ServerRequestInterface $request) {
    return new JsonResponse([
        ['id' => 2, 'title' => 'Second blog post'],
        ['id' => 1, 'title' => 'First blog post'],
    ]);
});
$routes->get('blog_show', '/blog/{id}', function (ServerRequestInterface $request) {
    $id = $request->getAttribute('id');
    if ($id >2) {
        return new JsonResponse(['error' => 'Undefined page'], 404);
    } else {
        return new JsonResponse(['id' => $id, 'title' => 'Post #' . $id]);
    }
}, ['id' => '\d+']);

$router = new Router($routes);

### Running

$request = ServerRequestFactory::fromGlobals();
try {
    $result = $router->match($request);
    foreach ($result->getAttributes() as  $attribute => $value) {
        $request = $request->withAttribute($attribute, $value);
    }
    $action = $result->getHandler();
    $response = $action($request);
} catch (Exception $exception) {
    $response = new JsonResponse(['error' => 'Undefined page']);
}

### Postprocessing

$response = $response->withHeader('X-Developer', 'CasparOne');

### Sending

$emitter = new Emitter();
$emitter->emit($response);