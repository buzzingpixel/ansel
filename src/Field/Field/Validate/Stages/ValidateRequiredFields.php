<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field\Validate\Stages;

use BuzzingPixel\Ansel\Field\Field\Validate\ValidateFieldPayload;
use BuzzingPixel\Ansel\Field\Settings\FieldSettingCustomField;

class ValidateRequiredFields implements ValidationStage
{
    private ValidateRequiredTextField $validateRequiredTextField;

    public function __construct(
        ValidateRequiredTextField $validateRequiredTextField
    ) {
        $this->validateRequiredTextField = $validateRequiredTextField;
    }

    public function __invoke(
        ValidateFieldPayload $payload
    ): ValidateFieldPayload {
        $payload->settings->customFields()->map(
            function (FieldSettingCustomField $customField) use (
                $payload
            ): void {
                switch ($customField->type()) {
                    case 'text':
                        $this->validateRequiredTextField->validate(
                            $customField,
                            $payload
                        );
                        break;
                    default:
                }
            }
        );

        return $payload;
    }
}
