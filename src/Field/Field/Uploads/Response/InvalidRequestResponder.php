<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field\Uploads\Response;

use Psr\Http\Message\ResponseInterface;

use function json_encode;

class InvalidRequestResponder
{
    public function respond(
        ResponseInterface $response,
        ValidationResponse $validationResponse
    ): ResponseInterface {
        $response = $response->withStatus(400)->withHeader(
            'Content-type',
            'application/json',
        );

        $response->getBody()->write((string) json_encode([
            'type' => 'error',
            'message' => $validationResponse->errorMessage(),
        ]));

        return $response;
    }
}
