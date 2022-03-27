<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field\Uploads\Response;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use function dd;

class PostImageUploadResponseFactory
{
    private ValidateRequest $validateRequest;

    private InvalidRequestResponder $invalidRequestResponder;

    public function __construct(
        ValidateRequest $validateRequest,
        InvalidRequestResponder $invalidRequestResponder
    ) {
        $this->validateRequest         = $validateRequest;
        $this->invalidRequestResponder = $invalidRequestResponder;
    }

    public function createResponse(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {
        $validation = $this->validateRequest->validate(
            $request,
        );

        if ($validation->isNotValid()) {
            return $this->invalidRequestResponder->respond(
                $response,
                $validation,
            );
        }

        dd($validation);
    }
}
