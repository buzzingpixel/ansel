<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field;

use BuzzingPixel\Ansel\Field\Field\PostedFieldData\PostedData;
use BuzzingPixel\Ansel\Field\Settings\FieldSettingsCollection;

use function dd;

class ValidateFieldAction
{
    public function validate(
        FieldSettingsCollection $fieldSettings,
        PostedData $postedFieldData
    ): void {
        dd('here', $postedFieldData);
    }
}
