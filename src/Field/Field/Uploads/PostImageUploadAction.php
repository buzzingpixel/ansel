<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field\Uploads;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use function dd;
use function json_encode;

class PostImageUploadAction
{
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {
        dd($request);

        $response = $response->withHeader(
            'Content-type',
            'application/json',
        );

        $response->getBody()->write((string) json_encode([
            'foo' => 'bar',
            'baz' => 'foo',
        ]));

        return $response;
    }
}
