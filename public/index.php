<?php

declare(strict_types=1);

use function DI\get;
use function DI\create;
use BareBone\HelloWorld;
use DI\ContainerBuilder;
use Laminas\Diactoros\Response;
use Middlewares\RequestHandler;
use Psr\Http\Message\ResponseInterface;
use Laminas\Diactoros\ServerRequestFactory;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use Laminas\HttpHandlerRunner\Emitter\SapiStreamEmitter;

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

$request = ServerRequestFactory::fromGlobals();

// print_r($request->getHeaders());

// return;

$router = new League\Route\Router;
$router->map('GET', '/', function(ServerRequestInterface $request){
    $response = new Response;
    $response->getBody()->write("<html><head></head><body><h2>Hello</h2></body></html>");
    return $response;
});
$router->map('GET', '/hello', $container->get(HelloWorld::class));

$response = $router->dispatch($request);

// emit using conditions
if(! $response->hasHeader('Content-Range') 
    && ! $response->hasHeader('Content-Disposition')){
        // less overhead, can use the normal stream
        $emitter = new SapiEmitter;
        return $emitter->emit($response);
    }
else{
    // more overhead, needs the SapiStreamEmitter 
    $emitter = new SapiStreamEmitter;
    return $emitter->emit($response);
}

// $helloWorld = $container->get(\BareBone\HelloWorld::class);
// $helloWorld();