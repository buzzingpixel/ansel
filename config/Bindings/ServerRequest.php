<?php

declare(strict_types=1);

namespace BuzzingPixel\AnselConfig\Bindings;

use Psr\Http\Message\ServerRequestInterface;
use Slim\Factory\ServerRequestCreatorFactory;

class ServerRequest
{
    /**
     * @return callable[]
     */
    public static function get(): array
    {
        return [
            ServerRequestInterface::class => static function (): ServerRequestInterface {
                return ServerRequestCreatorFactory::create()->createServerRequestFromGlobals();
            },
        ];
    }
}
