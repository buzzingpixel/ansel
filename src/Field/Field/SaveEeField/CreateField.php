<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field\SaveEeField;

use BuzzingPixel\Ansel\Field\Field\Persistence\AnselFieldEeRecord;
use BuzzingPixel\Ansel\Field\Field\PostedFieldData\PostedFieldData;
use BuzzingPixel\Ansel\Field\Field\PostedFieldData\PostedImage;

// phpcs:disable Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps

class CreateField
{
    public function create(
        PostedImage $image,
        PostedFieldData $fieldData
    ): AnselFieldEeRecord {
        $record = new AnselFieldEeRecord();

        $record->ansel_image_ansel_id = $image->id();

        $record->handle = $fieldData->handle();

        $record->value = (string) $fieldData->value();

        return $record;
    }
}
