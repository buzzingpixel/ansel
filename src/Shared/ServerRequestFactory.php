<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Shared;

use Psr\Http\Message\ServerRequestInterface;
use Slim\Factory\ServerRequestCreatorFactory;

class ServerRequestFactory
{
    public function createFromGlobals(): ServerRequestInterface
    {
        return ServerRequestCreatorFactory::create()
            ->createServerRequestFromGlobals();
    }
}
