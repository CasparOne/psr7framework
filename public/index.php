<?php
chdir(dirname(__DIR__));


use App\Http\Action\AboutAction;
use App\Http\Action\Blog\IndexAction;
use App\Http\Action\Blog\ShowAction;
use App\Http\Action\HelloAction;
use Framework\Http\ActionResolver;
use Framework\Http\Router\Exception\RequestNotMatchedException;
use Framework\Http\Router\RouteCollection;
use Framework\Http\Router\Router;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\ServerRequestFactory;
use Zend\HttpHandlerRunner\Emitter\SapiEmitter;


require 'vendor/autoload.php';
### Initialization
/*
 * Creating route collection
 */
$routes = new RouteCollection();

$routes->get('home', '/', HelloAction::class);

$routes->get('about', '/about', AboutAction::class);

$routes->get('/blog', '/blog', IndexAction::class);

$routes->get('/blog_show', '/blog/{id}', ShowAction::class,['id' => '\d+']);

// Router initialization with previously created RouteCollection
$router = new Router($routes);

// Creating Resolver with some actions creation logic
$resolver = new ActionResolver();

### Running
//Initialization a new Request object
$request = ServerRequestFactory::fromGlobals();

try {
    // Parsing current route
    $result = $router->match($request);
    // Getting Attributes from parsing result and setting up the Request
    foreach ($result->getAttribute() as $attribute => $value) {
        $request = $request->withAttribute($attribute, $value);
    }
    // Getting Handler name
    $handler = $result->getHandler();
    // Setting action
    $action = $resolver->resolve($handler);
    // Init Response object to return it
    $response = $action($request);
} catch (RequestNotMatchedException $exception) {
    $response = new HtmlResponse('Undefined Page', 404);
}

### Postprocessing
// Add some headers to response
$response = $response->withHeader('X-Developer', 'CasparOne');

### Sending

$emitter = new SapiEmitter();
$emitter->emit($response);