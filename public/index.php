<?php
chdir(dirname(__DIR__));

use App\Http\Action\AboutAction;
use App\Http\Action\Blog\IndexAction;
use App\Http\Action\Blog\ShowAction;
use App\Http\Action\CabinetAction;
use App\Http\Action\HelloAction;
use App\Http\Middleware\BasicAuthMiddleware;
use App\Http\Middleware\CredentialsMiddleware;
use App\Http\Middleware\ErrorHandlerMiddleware;
use App\Http\Middleware\NotFoundHandler;
use App\Http\Middleware\ProfilerMiddleware;
use Aura\Router\RouterContainer;
use Framework\Http\Application;
use Framework\Http\Middleware\DispatchMiddleware;
use Framework\Http\Middleware\RouteMiddleware;
use Framework\Http\Pipeline\MiddlewareResolver;
use Framework\Http\Router\AuraRouterAdapter;
use Framework\Http\Router\Exception\RequestNotMatchedException;
use Zend\Diactoros\ServerRequestFactory;
use Zend\HttpHandlerRunner\Emitter\SapiEmitter;

require 'vendor/autoload.php';
###############################################
# Initialization
###############################################
$aura = new RouterContainer();
$routes = $aura->getMap();
$params = [
    'debug' => true,
    'users' => [
        'admin' => 'password',
        'user'  => 'pass2'
    ],
];


// Routes settings
$routes->get('cabinet', '/cabinet',
    [
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
// Resolver initialization
$resolver = new MiddlewareResolver();
// App initialization
$app = new Application($resolver, new NotFoundHandler());


// Error handler middleware
$app->pipe(new ErrorHandlerMiddleware($params['debug']));
// Add some headers to response
$app->pipe(CredentialsMiddleware::class);
// Lazy load class Profiler (with closures)
$app->pipe($resolver->resolve(ProfilerMiddleware::class));
// Initialization Route middleware
$app->pipe(new RouteMiddleware($router));
// Initialization Dispatch middleware
$app->pipe(new DispatchMiddleware($resolver));


### Running
$request = ServerRequestFactory::fromGlobals();
$response = $app->run($request);


### Sending

$emitter = new SapiEmitter();
$emitter->emit($response);