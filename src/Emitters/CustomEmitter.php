<?php

declare(strict_types=1);

namespace BareBone\Emitters;

use Psr\Http\Message\ResponseInterface;
use Narrowspark\HttpEmitter\AbstractSapiEmitter;

class CustomEmitter implements AbstractSapiEmitter
{
    private $emitter;

    public function __construct(AbstractSapiEmitter $emitter)
    {
        $this->emitter = $emitter;
    }

    public function emit(ResponseInterface $response): bool
    {
        return $this->emitter->emit($response);
    }
}