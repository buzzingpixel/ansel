<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field\Uploads\Response;

use BuzzingPixel\Ansel\Field\Field\Uploads\UploadKey\ValidateUploadKeyContract;
use Psr\Http\Message\ServerRequestInterface;

use function is_array;

class ValidateUploadKeyFromRequest
{
    private ValidateUploadKeyContract $validateUploadKey;

    public function __construct(ValidateUploadKeyContract $validateUploadKey)
    {
        $this->validateUploadKey = $validateUploadKey;
    }

    public function validate(ServerRequestInterface $request): bool
    {
        $parsedBody = $request->getParsedBody();

        if (! is_array($parsedBody)) {
            return false;
        }

        $uploadKey = (string) ($parsedBody['uploadKey'] ?? 'no-key');

        return $this->validateUploadKey->validate($uploadKey);
    }
}
