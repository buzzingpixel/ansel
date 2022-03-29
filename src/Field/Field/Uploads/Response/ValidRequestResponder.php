<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field\Uploads\Response;

use BuzzingPixel\Ansel\Field\Field\Uploads\Cache\CacheUploadedFileResult;
use Psr\Http\Message\ResponseInterface;

use function json_encode;

class ValidRequestResponder
{
    public function respond(
        ResponseInterface $response,
        CacheUploadedFileResult $result
    ): ResponseInterface {
        $response = $response->withHeader(
            'Content-type',
            'application/json',
        );

        $response->getBody()->write((string) json_encode(
            $result->asArray(),
        ));

        return $response;
    }
}
