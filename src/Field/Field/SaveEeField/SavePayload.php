<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field\SaveEeField;

use BuzzingPixel\Ansel\Field\Field\FieldMetaEe;
use BuzzingPixel\Ansel\Field\Field\PostedFieldData\PostedData;
use BuzzingPixel\Ansel\Field\Settings\FieldSettingsCollection;

class SavePayload
{
    private PostedData $data;

    private FieldSettingsCollection $fieldSettings;

    private FieldMetaEe $fieldMeta;

    public function __construct(
        PostedData $data,
        FieldSettingsCollection $fieldSettings,
        FieldMetaEe $fieldMeta
    ) {
        $this->data          = $data;
        $this->fieldSettings = $fieldSettings;
        $this->fieldMeta     = $fieldMeta;
    }

    public function data(): PostedData
    {
        return $this->data;
    }

    public function fieldSettings(): FieldSettingsCollection
    {
        return $this->fieldSettings;
    }

    public function fieldMeta(): FieldMetaEe
    {
        return $this->fieldMeta;
    }
}
