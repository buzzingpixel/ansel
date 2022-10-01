<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field\Validate;

use BuzzingPixel\Ansel\Field\Field\PostedFieldData\PostedData;
use BuzzingPixel\Ansel\Field\Settings\FieldSettingsCollection;

class ValidateFieldPayload
{
    public PostedData $data;

    public FieldSettingsCollection $settings;

    public ValidatedFieldResult $validatedFieldResult;

    public function __construct(
        PostedData $data,
        FieldSettingsCollection $settings,
        ValidatedFieldResult $validatedFieldResult
    ) {
        $this->data                 = $data;
        $this->settings             = $settings;
        $this->validatedFieldResult = $validatedFieldResult;
    }
}
