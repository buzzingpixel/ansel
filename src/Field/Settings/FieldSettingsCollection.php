<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Settings;

class FieldSettingsCollection
{
    private FieldSettingItemDirectory $uploadLocation;

    private FieldSettingItemDirectory $saveLocation;

    private FieldSettingItemString $minQty;

    private FieldSettingItemString $maxQty;

    private FieldSettingItemBoolean $preventUploadOverMax;

    private FieldSettingItemInteger $quality;

    private FieldSettingItemBoolean $forceJpg;

    private FieldSettingItemBoolean $retinaMode;

    private FieldSettingItemInteger $minWidth;

    private FieldSettingItemInteger $minHeight;

    private FieldSettingItemInteger $maxWidth;

    private FieldSettingItemInteger $maxHeight;

    private FieldSettingItemString $ratio;

    private FieldSettingCustomFieldCollection $customFields;

    public function __construct()
    {
        $this->uploadLocation = new FieldSettingItemDirectory(
            'uploadLocation',
            'upload_directory',
            'upload_directory_explain',
            true,
        );

        $this->saveLocation = new FieldSettingItemDirectory(
            'saveLocation',
            'save_directory',
            'save_directory_explain',
            true,
        );

        $this->minQty = new FieldSettingItemString(
            'minQty',
            'min_quantity',
            'optional',
        );

        $this->maxQty = new FieldSettingItemString(
            'maxQty',
            'max_quantity',
            'optional',
        );

        $this->preventUploadOverMax = new FieldSettingItemBoolean(
            'preventUploadOverMax',
            'prevent_upload_over_max',
            'prevent_upload_over_max_explain',
        );

        $this->quality = new FieldSettingItemInteger(
            'quality',
            'image_quality',
            'specify_jpeg_image_quality',
            true,
        );

        $this->forceJpg = new FieldSettingItemBoolean(
            'forceJpg',
            'force_jpeg',
            'force_jpeg_explain',
        );

        $this->retinaMode = new FieldSettingItemBoolean(
            'retinaMode',
            'retina_mode',
            'retina_mode_explain',
        );

        $this->minWidth = new FieldSettingItemInteger(
            'minWidth',
            'min_width',
            'optional',
        );

        $this->minHeight = new FieldSettingItemInteger(
            'minHeight',
            'min_height',
            'optional',
        );

        $this->maxWidth = new FieldSettingItemInteger(
            'maxWidth',
            'max_width',
            'optional',
        );

        $this->maxHeight = new FieldSettingItemInteger(
            'maxHeight',
            'max_height',
            'optional',
        );

        $this->ratio = new FieldSettingItemString(
            'ratio',
            'crop_ratio',
            'crop_ratio_explain',
        );

        $this->customFields = new FieldSettingCustomFieldCollection();
    }

    /**
     * @return FieldSettingItemContract[]
     */
    public function asArray(): array
    {
        return [
            'uploadLocation' => $this->uploadLocation,
            'saveLocation' => $this->saveLocation,
            'minQty' => $this->minQty,
            'maxQty' => $this->maxQty,
            'preventUploadOverMax' => $this->preventUploadOverMax,
            'quality' => $this->quality,
            'forceJpg' => $this->forceJpg,
            'retinaMode' => $this->retinaMode,
            'minWidth' => $this->minWidth,
            'minHeight' => $this->minHeight,
            'maxWidth' => $this->maxWidth,
            'maxHeight' => $this->maxHeight,
            'ratio' => $this->ratio,
        ];
    }

    public function uploadLocation(): FieldSettingItemDirectory
    {
        return $this->uploadLocation;
    }

    public function saveLocation(): FieldSettingItemDirectory
    {
        return $this->saveLocation;
    }

    public function minQty(): FieldSettingItemString
    {
        return $this->minQty;
    }

    public function maxQty(): FieldSettingItemString
    {
        return $this->maxQty;
    }

    public function preventUploadOverMax(): FieldSettingItemBoolean
    {
        return $this->preventUploadOverMax;
    }

    public function quality(): FieldSettingItemInteger
    {
        return $this->quality;
    }

    public function forceJpg(): FieldSettingItemBoolean
    {
        return $this->forceJpg;
    }

    public function retinaMode(): FieldSettingItemBoolean
    {
        return $this->retinaMode;
    }

    public function minWidth(): FieldSettingItemInteger
    {
        return $this->minWidth;
    }

    public function minHeight(): FieldSettingItemInteger
    {
        return $this->minHeight;
    }

    public function maxWidth(): FieldSettingItemInteger
    {
        return $this->maxWidth;
    }

    public function maxHeight(): FieldSettingItemInteger
    {
        return $this->maxHeight;
    }

    public function ratio(): FieldSettingItemString
    {
        return $this->ratio;
    }

    public function customFields(): FieldSettingCustomFieldCollection
    {
        return $this->customFields;
    }
}
