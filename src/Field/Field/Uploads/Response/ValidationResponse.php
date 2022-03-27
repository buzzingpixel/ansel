<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field\Uploads\Response;

use BuzzingPixel\Ansel\Shared\Psr\EmptyUploadedFile;
use Psr\Http\Message\UploadedFileInterface;

class ValidationResponse
{
    private bool $isValid;

    private bool $isNotValid;

    private string $errorMessage;

    private UploadedFileInterface $uploadedFile;

    public function __construct(
        bool $isValid,
        string $errorMessage = '',
        ?UploadedFileInterface $uploadedFile = null
    ) {
        $this->isValid = $isValid;

        $this->isNotValid = ! $isValid;

        $this->errorMessage = $errorMessage;

        if ($uploadedFile === null) {
            $this->uploadedFile = new EmptyUploadedFile();

            return;
        }

        $this->uploadedFile = $uploadedFile;
    }

    public function isValid(): bool
    {
        return $this->isValid;
    }

    public function isNotValid(): bool
    {
        return $this->isNotValid;
    }

    public function errorMessage(): string
    {
        return $this->errorMessage;
    }

    public function uploadedFile(): UploadedFileInterface
    {
        return $this->uploadedFile;
    }
}
