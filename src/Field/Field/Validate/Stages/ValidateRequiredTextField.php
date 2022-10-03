<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field\Validate\Stages;

use BuzzingPixel\Ansel\Field\Field\PostedFieldData\PostedImage;
use BuzzingPixel\Ansel\Field\Field\Validate\ValidateFieldPayload;
use BuzzingPixel\Ansel\Field\Settings\FieldSettingCustomField;
use BuzzingPixel\Ansel\Translations\TranslatorContract;

class ValidateRequiredTextField
{
    private TranslatorContract $translator;

    public function __construct(TranslatorContract $translator)
    {
        $this->translator = $translator;
    }

    public function validate(
        FieldSettingCustomField $customField,
        ValidateFieldPayload $payload
    ): void {
        if (! $customField->required()) {
            return;
        }

        $payload->data->postedImages()->map(
            function (PostedImage $image) use (
                $payload,
                $customField
            ): void {
                $fieldData = $image->postedFieldDataCollection()->findByHandle(
                    $customField->handle(),
                );

                if ($fieldData === null) {
                    $this->invalidate(
                        $customField,
                        $payload
                    );

                    return;
                }
            }
        );
    }

    private function invalidate(
        FieldSettingCustomField $customField,
        ValidateFieldPayload $payload
    ): void {
        $message = $this->translator->getLineWithReplacements(
            'custom_field_x_required',
            [
                '{{fieldLabel}}' => $customField->label(),
            ],
        );

        if ($payload->validatedFieldResult->hasErrorMessage($message)) {
            return;
        }

        $payload->validatedFieldResult->addErrorMessage($message);
    }
}
