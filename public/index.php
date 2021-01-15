<?php

declare(strict_types=1);

use Relay\Relay;
use function DI\get;
use function DI\create;
use BareBone\HelloWorld;
use DI\ContainerBuilder;
use Middlewares\FastRoute;
use FastRoute\RouteCollector;
use Laminas\Diactoros\Response;
use Middlewares\RequestHandler;
use function FastRoute\simpleDispatcher;
use Laminas\Diactoros\ServerRequestFactory;
use Narrowspark\HttpEmitter\SapiEmitter;

require_once dirname(__DIR__).'/vendor/autoload.php';

$containerBuilder = new ContainerBuilder();
$containerBuilder->useAutowiring(false);
$containerBuilder->useAnnotations(false);
$containerBuilder->addDefinitions([
    HelloWorld::class => 
    create(HelloWorld::class)
    ->constructor(get('Foo'), get('Response')),
    'Foo' => 'Bar',
    'Response' => function(){
        return new Response();
    }
]);

$container = $containerBuilder->build();

$routes = simpleDispatcher(function(RouteCollector $r){
    $r->get('/hello', HelloWorld::class);
});

$middlewareQueue[] = new FastRoute($routes);
$middlewareQueue[] = new RequestHandler($container);

$requestHandler = new Relay($middlewareQueue);
$response = $requestHandler->handle(ServerRequestFactory::fromGlobals());

// emit using SapiEmitter
$emitter = new SapiEmitter();
return $emitter->emit($response);

// $helloWorld = $container->get(\BareBone\HelloWorld::class);
// $helloWorld();