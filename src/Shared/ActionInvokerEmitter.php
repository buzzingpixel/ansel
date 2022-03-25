<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Shared;

use BuzzingPixel\Ansel\Shared\Php\InternalFunctions;
use Psr\Http\Message\ResponseFactoryInterface;
use Slim\Psr7\Factory\ResponseFactory;
use Slim\ResponseEmitter;

class ActionInvokerEmitter
{
    private ResponseEmitter $responseEmitter;

    private ResponseFactoryInterface $responseFactory;

    private InternalFunctions $internalFunctions;

    private ServerRequestFactory $serverRequestFactory;

    public function __construct(
        ResponseEmitter $responseEmitter,
        ResponseFactory $responseFactory,
        InternalFunctions $internalFunctions,
        ServerRequestFactory $serverRequestFactory
    ) {
        $this->responseEmitter      = $responseEmitter;
        $this->responseFactory      = $responseFactory;
        $this->internalFunctions    = $internalFunctions;
        $this->serverRequestFactory = $serverRequestFactory;
    }

    public function invokeAndEmit(callable $action): void
    {
        $request = $this->serverRequestFactory->createFromGlobals();

        $response = $action($request, $this->responseFactory->createResponse());

        $this->responseEmitter->emit($response);

        $this->internalFunctions->doExit();
    }
}
