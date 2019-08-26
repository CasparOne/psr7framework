<?php
chdir(dirname(__DIR__));

use App\Http\Action\AboutAction;
use App\Http\Action\Blog\IndexAction;
use App\Http\Action\Blog\ShowAction;
use App\Http\Action\CabinetAction;
use App\Http\Action\HelloAction;
use App\Http\Middleware\BasicAuthMiddleware;
use App\Http\Middleware\NotFoundHandler;
use App\Http\Middleware\ProfilerMiddleware;
use Aura\Router\RouterContainer;
use Framework\Http\MiddlewareResolver;
use Framework\Http\Pipeline\Pipeline;
use Framework\Http\Router\AuraRouterAdapter;
use Framework\Http\Router\Exception\RequestNotMatchedException;
use Zend\Diactoros\ServerRequestFactory;
use Zend\HttpHandlerRunner\Emitter\SapiEmitter;

require 'vendor/autoload.php';
### Initialization
$aura = new RouterContainer();
$routes = $aura->getMap();
$params = [
    'users' => [
        'admin' => 'password',
        'user'  => 'pass2'
    ],
];


// Routes settings
$routes->get('cabinet', '/cabinet', [
    ProfilerMiddleware::class,
    new BasicAuthMiddleware($params['users']),
    CabinetAction::class,
]);

$routes->get('home', '/', HelloAction::class);

$routes->get('about', '/about', AboutAction::class);

$routes->get('/blog', '/blog', IndexAction::class);

$routes->get('/blog_show', '/blog/{id}', ShowAction::class)->tokens(['id' => '\d+']);


// Router initialization
$router = new AuraRouterAdapter($aura);
// Creating Resolver with some actions creation logic
$resolver = new MiddlewareResolver();
//Initialization a new Request object
$pipeline = new Pipeline();
// Lazy load class Profiler (with closures)
$pipeline->pipe($resolver->resolve(ProfilerMiddleware::class));

### Running
$request = ServerRequestFactory::fromGlobals();
try {
    // Parsing current route
    $result = $router->match($request);
    // Getting Attributes from parsing result and setting up the Request
    foreach ($result->getAttribute() as $attribute => $value) {
        $request = $request->withAttribute($attribute, $value);
    }
    // Getting Handler name
    $handlers = $result->getHandler();
    foreach (is_array($handlers) ? $handlers : [$handlers] as $handler) {
        $pipeline->pipe($resolver->resolve($handler));
    }
} catch (RequestNotMatchedException $exception) {}

$response = $pipeline($request, new NotFoundHandler());


### Postprocessing
// Add some headers to response
$response = $response->withHeader('X-Developer', 'CasparOne');

### Sending

$emitter = new SapiEmitter();
$emitter->emit($response);