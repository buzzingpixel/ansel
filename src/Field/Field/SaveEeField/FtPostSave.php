<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field\SaveEeField;

use BuzzingPixel\Ansel\Field\Field\EeContentType;
use BuzzingPixel\Ansel\Field\Field\FieldMetaEe;
use BuzzingPixel\Ansel\Field\Field\PostedFieldData\PostedData;
use BuzzingPixel\Ansel\Field\Settings\ExpressionEngine\FieldSettingsFromRaw;

use function is_array;
use function is_string;
use function json_decode;

class FtPostSave
{
    private SaveFieldAction $saveFieldAction;

    private FieldSettingsFromRaw $fieldSettingsFromRaw;

    public function __construct(
        SaveFieldAction $saveFieldAction,
        FieldSettingsFromRaw $fieldSettingsFromRaw
    ) {
        $this->saveFieldAction      = $saveFieldAction;
        $this->fieldSettingsFromRaw = $fieldSettingsFromRaw;
    }

    /**
     * @param mixed   $data
     * @param mixed[] $rawEeFieldSettings
     * @param scalar  $fieldId
     * @param scalar  $fieldName
     * @param scalar  $contentType
     * @param scalar  $channelId
     * @param scalar  $contentId
     */
    public function save(
        $data,
        array $rawEeFieldSettings,
        $fieldId,
        $fieldName,
        $contentType,
        $channelId,
        $contentId
    ): void {
        $data = is_string($data) ? $data : '';

        $data = json_decode($data, true);

        $data = is_array($data) ? $data : [];

        $this->saveFieldAction->save(
            new SavePayload(
                PostedData::fromArray($data),
                $this->fieldSettingsFromRaw->get(
                    $rawEeFieldSettings,
                    true
                ),
                new FieldMetaEe(
                    (int) $fieldId,
                    (string) $fieldName,
                    new EeContentType((string) $contentType),
                    (int) $channelId,
                    (int) $contentId,
                ),
            ),
        );
    }
}
