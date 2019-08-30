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
use Framework\Container\Container;
use Framework\Http\Application;
use Framework\Http\Middleware\DispatchMiddleware;
use Framework\Http\Middleware\RouteMiddleware;
use Framework\Http\Pipeline\MiddlewareResolver;
use Framework\Http\Router\AuraRouterAdapter;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequestFactory;
use Zend\HttpHandlerRunner\Emitter\SapiEmitter;

require 'vendor/autoload.php';

###############################################
# Config
###############################################

$container = new Container();
$container->set('debug', true);
$container->set('users', ['admin' => 'password']);
$container->set('dsn', 'mysql:localhost;dbname=db');
$container->set('username', 'username');
$container->set('password', 'secret');
$container->set('db', function (Container $c) {
    return new \PDO(
        $c->get('dsn'),
        $c->get('username'),
        $c->get('password')
    );
});

$db = $container->get('db');

###############################################
# Initialization
###############################################

$aura = new RouterContainer();
$routes = $aura->getMap();


$routes->get('home', '/', HelloAction::class);
$routes->get('about', '/about', AboutAction::class);
$routes->get('cabinet', '/cabinet', CabinetAction::class);
$routes->get('blog', '/blog', IndexAction::class);
$routes->get('blog_show', '/blog/{id}', ShowAction::class)->tokens(['id' => '\d+']);


$router = new AuraRouterAdapter($aura);
$resolver = new MiddlewareResolver();
$app = new Application($resolver, new NotFoundHandler(), new Response());

$app->pipe(new ErrorHandlerMiddleware($container->get('debug')));
$app->pipe(CredentialsMiddleware::class);
$app->pipe(ProfilerMiddleware::class);
$app->pipe(new RouteMiddleware($router));
$app->pipe('cabinet', new BasicAuthMiddleware($container->get('users')));
$app->pipe(new DispatchMiddleware($resolver));

### Running
$request = ServerRequestFactory::fromGlobals();
$response = $app->run($request, new Response());

### Sending
$emitter = new SapiEmitter();
$emitter->emit($response);
