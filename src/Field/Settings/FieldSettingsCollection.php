<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Settings;

use function array_filter;
use function array_map;
use function array_walk;
use function assert;
use function is_array;
use function is_string;

class FieldSettingsCollection
{
    private FieldSettingItemDirectory $uploadLocation;

    private FieldSettingItemDirectory $saveLocation;

    private FieldSettingItemInteger $minQty;

    private FieldSettingItemInteger $maxQty;

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

    /**
     * @param mixed[] $fieldSettings
     */
    public static function fromFieldArray(array $fieldSettings): self
    {
        $collection = new self();

        array_walk(
            $fieldSettings,
            static function ($item, $index) use ($collection): void {
                /** @phpstan-ignore-next-line */
                $setting = $collection->{$index}();

                if ($setting instanceof FieldSettingItemContract) {
                    assert(is_string($item));

                    $setting->setValueFromString($item);

                    return;
                }

                assert($setting instanceof FieldSettingCustomFieldCollection);

                assert(is_array($item));

                $item = array_filter(
                    $item,
                    static fn ($val) => is_array($val)
                );

                array_walk(
                    $item,
                    static function (array $val) use ($setting): void {
                        $required = (string) ($val['required'] ?? '');

                        $setting->addField(new FieldSettingCustomField(
                            (string) ($val['label'] ?? ''),
                            (string) ($val['handle'] ?? ''),
                            (string) ($val['type'] ?? ''),
                            $required === '1',
                        ));
                    }
                );
            },
        );

        return $collection;
    }

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

        $this->minQty = new FieldSettingItemInteger(
            'minQty',
            'min_quantity',
            'optional',
        );

        $this->maxQty = new FieldSettingItemInteger(
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

    /**
     * @return mixed[]
     */
    public function map(callable $callback): array
    {
        return array_map($callback, $this->asArray());
    }

    public function uploadLocation(): FieldSettingItemDirectory
    {
        return $this->uploadLocation;
    }

    public function saveLocation(): FieldSettingItemDirectory
    {
        return $this->saveLocation;
    }

    public function minQty(): FieldSettingItemInteger
    {
        return $this->minQty;
    }

    public function maxQty(): FieldSettingItemInteger
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
