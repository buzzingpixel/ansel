<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field\Validate;

use BuzzingPixel\Ansel\Field\Field\PostedFieldData\PostedData;
use BuzzingPixel\Ansel\Field\Settings\Craft\FieldSettingsFromRaw;
use BuzzingPixel\AnselCms\Craft\AnselCraftField;
use craft\base\ElementInterface;
use craft\errors\InvalidFieldException;

use function is_array;

class CraftValidateField
{
    private FieldSettingsFromRaw $fieldSettingsFromRaw;

    private ValidateFieldAction $validateFieldAction;

    public function __construct(
        ValidateFieldAction $validateFieldAction,
        FieldSettingsFromRaw $fieldSettingsFromRaw
    ) {
        $this->fieldSettingsFromRaw = $fieldSettingsFromRaw;
        $this->validateFieldAction  = $validateFieldAction;
    }

    /**
     * @throws InvalidFieldException
     */
    public function validate(
        ElementInterface $element,
        AnselCraftField $field
    ): void {
        $data = $element->getFieldValue((string) $field->handle);

        if (! is_array($data)) {
            $data = [];
        }

        $fieldSettings = $this->fieldSettingsFromRaw->get(
            $field->fieldSettings,
            $field->required,
        );

        $validatedFieldResult = $this->validateFieldAction->validate(
            $fieldSettings,
            PostedData::fromArray($data)
        );

        $validatedFieldResult->map(static fn (
            ValidatedFieldError $error
        ) => $element->addError(
            $field->handle,
            $error->errorMsg(),
        ));
    }
}
