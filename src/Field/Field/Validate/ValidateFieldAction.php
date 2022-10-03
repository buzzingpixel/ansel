<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field\Validate;

use BuzzingPixel\Ansel\Field\Field\PostedFieldData\PostedData;
use BuzzingPixel\Ansel\Field\Field\Validate\Stages\ValidateFieldMeetsMinQty;
use BuzzingPixel\Ansel\Field\Field\Validate\Stages\ValidateFieldNotOverMaxQty;
use BuzzingPixel\Ansel\Field\Field\Validate\Stages\ValidateRequiredFields;
use BuzzingPixel\Ansel\Field\Settings\FieldSettingsCollection;

class ValidateFieldAction
{
    private ValidateFieldMeetsMinQty $validateFieldMeetsMinQty;

    private ValidateFieldNotOverMaxQty $validateFieldNotOverMaxQty;

    private ValidateRequiredFields $validateRequiredFields;

    public function __construct(
        ValidateFieldMeetsMinQty $validateFieldMeetsMinQty,
        ValidateFieldNotOverMaxQty $validateFieldNotOverMaxQty,
        ValidateRequiredFields $validateRequiredFields
    ) {
        $this->validateFieldMeetsMinQty   = $validateFieldMeetsMinQty;
        $this->validateFieldNotOverMaxQty = $validateFieldNotOverMaxQty;
        $this->validateRequiredFields     = $validateRequiredFields;
    }

    public function validate(
        FieldSettingsCollection $settings,
        PostedData $data
    ): ValidatedFieldResult {
        return (new ValidationPipeline(...[
            $this->validateFieldMeetsMinQty,
            $this->validateFieldNotOverMaxQty,
            $this->validateRequiredFields,
        ]))->process(new ValidateFieldPayload(
            $data,
            $settings,
            new ValidatedFieldResult(),
        ))->validatedFieldResult;
    }
}
