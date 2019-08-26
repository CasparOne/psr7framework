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
use Framework\Http\ActionResolver;
use Framework\Http\Pipeline\Pipeline;
use Framework\Http\Router\AuraRouterAdapter;
use Framework\Http\Router\Exception\RequestNotMatchedException;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\ServerRequestFactory;
use Zend\HttpHandlerRunner\Emitter\SapiEmitter;

require 'vendor/autoload.php';
### Initialization
$aura = new RouterContainer();
$routes = $aura->getMap();
$params = [
    'users' => [
        'admin' => 'password1',
        'user'  => 'pass2'
    ],
];

// Router initialization
$router = new AuraRouterAdapter($aura);
// Creating Resolver with some actions creation logic
$resolver = new ActionResolver();

// Routes settings
$routes->get('cabinet', '/cabinet', function (ServerRequestInterface $request) use ($params){
    $pipeline = new Pipeline();
    $pipeline->pipe(new BasicAuthMiddleware($params['users']));
    $pipeline->pipe(new ProfilerMiddleware());
    $pipeline->pipe(new CabinetAction());

    return $pipeline($request, function () {
        return new NotFoundHandler();
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
    $handler = new NotFoundHandler();
    $response = $handler($request);
}

### Postprocessing
// Add some headers to response
$response = $response->withHeader('X-Developer', 'CasparOne');

### Sending

$emitter = new SapiEmitter();
$emitter->emit($response);