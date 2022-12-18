<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Settings\Craft;

use BuzzingPixel\Ansel\Field\Settings\FieldSettingsCollectionValidatorContract;
use BuzzingPixel\AnselCms\Craft\AnselCraftField;

use function count;

class ValidateSettings
{
    private FieldSettingsFromRaw $fieldSettingsFromRaw;

    private FieldSettingsCollectionValidatorContract $fieldSettingsValidator;

    public function __construct(
        FieldSettingsFromRaw $fieldSettingsFromRaw,
        FieldSettingsCollectionValidatorContract $fieldSettingsValidator
    ) {
        $this->fieldSettingsFromRaw   = $fieldSettingsFromRaw;
        $this->fieldSettingsValidator = $fieldSettingsValidator;
    }

    public function validate(AnselCraftField $field): bool
    {
        $errors = $this->fieldSettingsValidator->validate(
            $this->fieldSettingsFromRaw->get(
                /** @phpstan-ignore-next-line */
                $field->fieldSettings,
            ),
        );

        if (count($errors) < 1) {
            return true;
        }

        $field->addErrors($errors);

        return false;
    }
}
