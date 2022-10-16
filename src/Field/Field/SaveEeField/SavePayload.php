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

    private FieldMetaEe $fieldMetaEe;

    public function __construct(
        PostedData $data,
        FieldSettingsCollection $fieldSettings,
        FieldMetaEe $fieldMetaEe
    ) {
        $this->data          = $data;
        $this->fieldSettings = $fieldSettings;
        $this->fieldMetaEe   = $fieldMetaEe;
    }

    public function data(): PostedData
    {
        return $this->data;
    }

    public function fieldSettings(): FieldSettingsCollection
    {
        return $this->fieldSettings;
    }

    public function fieldMetaEe(): FieldMetaEe
    {
        return $this->fieldMetaEe;
    }
}
