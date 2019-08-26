<?php
chdir(dirname(__DIR__));

use App\Http\Action\AboutAction;
use App\Http\Action\Blog\IndexAction;
use App\Http\Action\Blog\ShowAction;
use App\Http\Action\CabinetAction;
use App\Http\Action\HelloAction;
use Aura\Router\RouterContainer;
use Framework\Http\ActionResolver;
use Framework\Http\Router\AuraRouterAdapter;
use Framework\Http\Router\Exception\RequestNotMatchedException;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\ServerRequestFactory;
use Zend\HttpHandlerRunner\Emitter\SapiEmitter;

require 'vendor/autoload.php';
### Initialization
$aura = new RouterContainer();
$routes = $aura->getMap();
$params = [
    'users' => [
        'admin' => 'password2',
        'user'  => 'pass2'
    ],
];
$auth = new App\Http\Middleware\BasicAuthMiddleware($params['users']);
$profiler = new App\Http\Middleware\ProfilerMiddleware();

// Router initialization
$router = new AuraRouterAdapter($aura);
// Creating Resolver with some actions creation logic
$resolver = new ActionResolver();

// Routes settings
$routes->get('cabinet', '/cabinet', function (ServerRequestInterface $request) use ($auth, $profiler){
    $cabinet = new CabinetAction();
    return $profiler($request, function (ServerRequestInterface $request) use ($auth, $cabinet) {
        return $auth($request, function (ServerRequestInterface $request) use ($cabinet) {
            return $cabinet($request);
        });
    });
});

$routes->get('home', '/', HelloAction::class);

$routes->get('about', '/about', AboutAction::class);

$routes->get('/blog', '/blog', IndexAction::class);

$routes->get('/blog_show', '/blog/{id}', ShowAction::class)->tokens(['id' => '\d+']);

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