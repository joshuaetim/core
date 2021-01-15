<?php

declare(strict_types=1);

namespace BareBone\Emitters;

use Psr\Http\Message\ResponseInterface;
use Narrowspark\HttpEmitter\AbstractSapiEmitter;

class CustomEmitter extends AbstractSapiEmitter
{
    /**
     * This class acts as a parent class calling either SapiEmitter or SapiStreamEmitter
     */

    private $emitter;

    public function __construct(AbstractSapiEmitter $emitter)
    {
        $this->emitter = $emitter;
    }

    public function emit(ResponseInterface $response): void
    {
        $this->emitter->emit($response);
    }
}