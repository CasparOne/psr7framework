<?php
chdir(dirname(__DIR__));


use Framework\Http\Router\Exception\RequestNotMatchedException;
use Framework\Http\Router\RouteCollection;
use Framework\Http\Router\Router;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\ServerRequestFactory;
use Zend\HttpHandlerRunner\Emitter\SapiEmitter;
use Zend\Diactoros\Response\JsonResponse;


require 'vendor/autoload.php';
### Initialization

$routes = new RouteCollection();

$routes->get('home', '/', function (ServerRequestInterface $request) {
    $name = $request->getQueryParams()['name'] ??  'GUEST';
    return new HtmlResponse('Hello, ' . $name . '!!');
});

$routes->get('about', '/about', function () {
    return new HtmlResponse('I am a Site. It\'s about section.' );
});

$routes->get('/blog', '/blog', function () {
    return new JsonResponse([
        ['id' => 2, 'title' => 'Second blog post'],
        ['id' => 1, 'title' => 'First blog post'],
    ]);
});
$routes->get('/blog_show', '/blog/{id}', function (ServerRequestInterface $request) {
    $id = $request->getAttribute('id');
    if ($id > 2) {
        return new JsonResponse(['error' => 'Undefined Page'], 404);
    }
    return new JsonResponse(['id' => $id, 'title' => 'Post #' . $id]);
}, ['id' => '\d+']);

$router = new Router($routes);

### Running
$request = ServerRequestFactory::fromGlobals();

try {
    $result = $router->match($request);
    foreach ($result->getAttribute() as $attribute => $value) {
        $request = $request->withAttribute($attribute, $value);
    }
    $action = $result->getHandler();
    $response = $action($request);
} catch (RequestNotMatchedException $exception) {
    $response = new HtmlResponse('Undefined Page', 404);
}

### Postprocessing

$response = $response->withHeader('X-Developer', 'CasparOne');

### Sending

$emitter = new SapiEmitter();
$emitter->emit($response);