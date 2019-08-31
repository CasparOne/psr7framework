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
use Framework\Http\Router\RouterInterface;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequestFactory;
use Zend\HttpHandlerRunner\Emitter\SapiEmitter;

require 'vendor/autoload.php';

###############################################
# Config
###############################################

$container = new Container();
$container->set('config', [
    'debug' => true,
    'users' => ['admin' => 'password']
]);

################################################
# Application Init
################################################
$container->set(Application::class, function (Container $c) {
    return new Application(
        $c->get(MiddlewareResolver::class),
        new NotFoundHandler(),
        new Response()
    );
});

################################################
# Services
################################################
// Middleware
$container->set(BasicAuthMiddleware::class, function (Container $c) {
    return new BasicAuthMiddleware($c->get('config')['users']);
});
$container->set(ErrorHandlerMiddleware::class, function (Container $c) {
    return new ErrorHandlerMiddleware($c->get('config')['debug']);
});
$container->set(RouteMiddleware::class, function (Container $c) {
    return new RouteMiddleware($c->get(RouterInterface::class));
});
// Resolver
$container->set(MiddlewareResolver::class, function () {
    return new MiddlewareResolver();
});
// Dispatcher
$container->set(DispatchMiddleware::class, function (Container $c) {
    return new DispatchMiddleware($c->get(MiddlewareResolver::class));
});
// Router
$container->set(
    RouterInterface::class,
    function () {
        $aura = new RouterContainer();
        $routes = $aura->getMap();
        $routes->get('home', '/', HelloAction::class);
        $routes->get('about', '/about', AboutAction::class);
        $routes->get('cabinet', '/cabinet', CabinetAction::class);
        $routes->get('blog', '/blog', IndexAction::class);
        $routes->get('blog_show', '/blog/{id}', ShowAction::class)->tokens(['id' => '\d+']);
        return new AuraRouterAdapter($aura);
    }
);


###############################################
# Initialization
###############################################
/** @var Application $app */
$app = $container->get(Application::class);

$app->pipe($container->get(ErrorHandlerMiddleware::class));
$app->pipe(CredentialsMiddleware::class);
$app->pipe(ProfilerMiddleware::class);
$app->pipe($container->get(RouteMiddleware::class));
$app->pipe('cabinet', $container->get(BasicAuthMiddleware::class));
$app->pipe($container->get(DispatchMiddleware::class));

### Running
$request = ServerRequestFactory::fromGlobals();
$response = $app->run($request, new Response());

### Sending
$emitter = new SapiEmitter();
$emitter->emit($response);
