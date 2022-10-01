<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field\Validate;

use BuzzingPixel\Ansel\Field\Field\PostedFieldData\PostedData;
use BuzzingPixel\Ansel\Field\Field\Validate\Stages\ValidateFieldMeetsMinQty;
use BuzzingPixel\Ansel\Field\Settings\FieldSettingsCollection;

class ValidateFieldAction
{
    private ValidateFieldMeetsMinQty $validateFieldMeetsMinQty;

    public function __construct(
        ValidateFieldMeetsMinQty $validateFieldMeetsMinQty
    ) {
        $this->validateFieldMeetsMinQty = $validateFieldMeetsMinQty;
    }

    public function validate(
        FieldSettingsCollection $settings,
        PostedData $data
    ): ValidatedFieldResult {
        // TODO: Additional validation
        return (new ValidationPipeline(...[
            $this->validateFieldMeetsMinQty,
        ]))->process(new ValidateFieldPayload(
            $data,
            $settings,
            new ValidatedFieldResult(),
        ))->validatedFieldResult;
    }
}
