<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field\Uploads\Response;

use BuzzingPixel\Ansel\Field\Field\Uploads\Cache\CacheUploadedFile;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class PostImageUploadResponseFactory
{
    private ValidateRequest $validateRequest;

    private CacheUploadedFile $cacheUploadedFile;

    private ValidRequestResponder $validRequestResponder;

    private InvalidRequestResponder $invalidRequestResponder;

    public function __construct(
        ValidateRequest $validateRequest,
        CacheUploadedFile $cacheUploadedFile,
        ValidRequestResponder $validRequestResponder,
        InvalidRequestResponder $invalidRequestResponder
    ) {
        $this->validateRequest         = $validateRequest;
        $this->cacheUploadedFile       = $cacheUploadedFile;
        $this->validRequestResponder   = $validRequestResponder;
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

        $cacheResult = $this->cacheUploadedFile->cache(
            $validation->uploadedFile(),
        );

        if (! $cacheResult->success()) {
            return $this->invalidRequestResponder->respond(
                $response,
                new ValidationResponse(
                    false,
                    $cacheResult->message(),
                ),
            );
        }

        return $this->validRequestResponder->respond(
            $response,
            $cacheResult,
        );
    }
}
