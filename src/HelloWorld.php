<?php

declare(strict_types=1);

namespace BareBone;

use Psr\Http\Message\ResponseInterface;

class HelloWorld
{
    private $foo;

    public function __construct(
        string $foo,
        ResponseInterface $response
        )
    {
        $this->foo = $foo;
        $this->response = $response;
    }

    public function __invoke(): ResponseInterface
    {
        $response = $this->response->withHeader('Content-Type', 'text/html');
        $response->getBody()
        ->write("<html><head></head><body>Hello, from {$this->foo}!</body></html>");

        return $response;
    }
}