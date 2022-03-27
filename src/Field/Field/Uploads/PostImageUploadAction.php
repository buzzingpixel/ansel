<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field\Uploads;

use BuzzingPixel\Ansel\Field\Field\Uploads\Response\PostImageUploadResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class PostImageUploadAction
{
    private PostImageUploadResponseFactory $responseFactory;

    public function __construct(PostImageUploadResponseFactory $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {
        return $this->responseFactory->createResponse(
            $request,
            $response
        );
    }
}
