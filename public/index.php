<?php
chdir(dirname(__DIR__));


use App\Http\Action\AboutAction;
use App\Http\Action\Blog\IndexAction;
use App\Http\Action\Blog\ShowAction;
use App\Http\Action\HelloAction;
use Framework\Http\Router\Exception\RequestNotMatchedException;
use Framework\Http\Router\RouteCollection;
use Framework\Http\Router\Router;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\ServerRequestFactory;
use Zend\HttpHandlerRunner\Emitter\SapiEmitter;


require 'vendor/autoload.php';
### Initialization

$routes = new RouteCollection();

$routes->get('home', '/', HelloAction::class);

$routes->get('about', '/about', AboutAction::class);

$routes->get('/blog', '/blog', IndexAction::class);

$routes->get('/blog_show', '/blog/{id}', ShowAction::class,['id' => '\d+']);

$router = new Router($routes);

### Running
$request = ServerRequestFactory::fromGlobals();

try {
    $result = $router->match($request);
    foreach ($result->getAttribute() as $attribute => $value) {
        $request = $request->withAttribute($attribute, $value);
    }
    $handler = $result->getHandler();
    $action = is_string($handler) ? new $handler : $handler;
    $response = $action($request);
} catch (RequestNotMatchedException $exception) {
    $response = new HtmlResponse('Undefined Page', 404);
}

### Postprocessing

$response = $response->withHeader('X-Developer', 'CasparOne');

### Sending

$emitter = new SapiEmitter();
$emitter->emit($response);