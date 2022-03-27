<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field;

use BuzzingPixel\Ansel\Field\Field\Uploads\UploadKey\GetUploadKeyContract;
use BuzzingPixel\Ansel\Field\Field\Uploads\UploadUrl\GetUploadUrlContract;

class GetFieldParameters
{
    private GetUploadUrlContract $getUploadUrl;

    private GetUploadKeyContract $getUploadKey;

    public function __construct(
        GetUploadUrlContract $getUploadUrl,
        GetUploadKeyContract $getUploadKey
    ) {
        $this->getUploadUrl = $getUploadUrl;
        $this->getUploadKey = $getUploadKey;
    }

    public function get(): FieldParametersCollection
    {
        return new FieldParametersCollection(
            $this->getUploadUrl->get(),
            $this->getUploadKey->get(),
        );
    }
}
