<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field\Validate;

use BuzzingPixel\Ansel\Field\Field\PostedFieldData\PostedData;
use BuzzingPixel\Ansel\Field\Settings\ExpressionEngine\FieldSettingsFromRaw;

use function implode;
use function is_array;

class EeFtValidateField
{
    private ValidateFieldAction $validateFieldAction;

    private FieldSettingsFromRaw $fieldSettingsFromRaw;

    public function __construct(
        ValidateFieldAction $validateFieldAction,
        FieldSettingsFromRaw $fieldSettingsFromRaw
    ) {
        $this->validateFieldAction  = $validateFieldAction;
        $this->fieldSettingsFromRaw = $fieldSettingsFromRaw;
    }

    /**
     * @param mixed   $data
     * @param mixed[] $rawEeFieldSettings
     *
     * @return bool|string
     */
    public function validate(
        $data,
        array $rawEeFieldSettings
    ) {
        $fieldSettings = $this->fieldSettingsFromRaw->get(
            $rawEeFieldSettings,
            true
        );

        $data = is_array($data) ? $data : [];

        $validatedFieldResult = $this->validateFieldAction->validate(
            $fieldSettings,
            PostedData::fromArray($data)
        );

        if ($validatedFieldResult->hasNoErrors()) {
            return true;
        }

        return implode(
            '<br>',
            $validatedFieldResult->map(static fn (
                ValidatedFieldError $error
            ) => $error->errorMsg())
        );
    }
}
