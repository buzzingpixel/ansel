<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field;

use BuzzingPixel\Ansel\Field\Field\Processing\ProcessingUrl\GetProcessingUrlContract;
use BuzzingPixel\Ansel\Field\Field\Uploads\UploadKey\GetUploadKeyContract;
use BuzzingPixel\Ansel\Field\Field\Uploads\UploadUrl\GetUploadUrlContract;
use BuzzingPixel\Ansel\Shared\Environment;

class GetFieldParameters
{
    private Environment $environment;

    private GetUploadUrlContract $getUploadUrl;

    private GetUploadKeyContract $getUploadKey;

    private GetProcessingUrlContract $getProcessingUrlContract;

    public function __construct(
        Environment $environment,
        GetUploadUrlContract $getUploadUrl,
        GetUploadKeyContract $getUploadKey,
        GetProcessingUrlContract $getProcessingUrlContract
    ) {
        $this->getUploadUrl             = $getUploadUrl;
        $this->getUploadKey             = $getUploadKey;
        $this->environment              = $environment;
        $this->getProcessingUrlContract = $getProcessingUrlContract;
    }

    public function get(): FieldParametersCollection
    {
        return new FieldParametersCollection(
            $this->environment->toString(),
            $this->getUploadUrl->get(),
            $this->getUploadKey->get(),
            $this->getProcessingUrlContract->get(),
        );
    }
}
