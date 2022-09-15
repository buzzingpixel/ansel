<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field;

use BuzzingPixel\Ansel\Field\Settings\FieldSettingsCollection;
use BuzzingPixel\Ansel\Translations\TranslatorContract;

class GetFieldRenderContext
{
    private TranslatorContract $translator;

    private GetFieldParameters $getFieldParameters;

    private DimensionsNotMetTranslationFactory $dimensionsNotMetTranslationFactory;

    public function __construct(
        TranslatorContract $translator,
        GetFieldParameters $getFieldParameters,
        DimensionsNotMetTranslationFactory $dimensionsNotMetTranslationFactory
    ) {
        $this->getFieldParameters                 = $getFieldParameters;
        $this->dimensionsNotMetTranslationFactory = $dimensionsNotMetTranslationFactory;
        $this->translator                         = $translator;
    }

    /**
     * @param scalar[] $platform
     *
     * @return mixed[]
     */
    public function get(
        FieldSettingsCollection $fieldSettings,
        string $fieldNameRoot,
        array $platform
    ): array {
        $maxQty = $fieldSettings->maxQty()->value();

        $minQty = $fieldSettings->minQty()->value();

        $limitedToXImages = $maxQty > 1 ?
            $this->translator->getLineWithReplacements(
                'limited_to_x_images',
                [
                    '{{qty}}' => (string) $maxQty,
                ]
            ) :
            $this->translator->getLine(
                'limited_to_1_image',
            );

        $fieldOverLimit = $maxQty > 1 ?
            $this->translator->getLineWithReplacements(
                'field_over_limit_qty',
                ['{{qty}}' => (string) $maxQty]
            ) :
            $this->translator->getLine('field_over_limit_1');

        $fieldUnderLimit = $minQty > 1 ?
            $this->translator->getLineWithReplacements(
                'field_requires_at_least_x_images',
                ['{{qty}}' => (string) $minQty]
            ) :
            $this->translator->getLine(
                'field_requires_at_least_1_image'
            );

        return [
            'fieldNameRoot' => $fieldNameRoot,
            'model' => new FieldRenderModel(
                $fieldSettings->asScalarArray(),
                $fieldSettings->customFields()->asScalarArray(),
                $this->getFieldParameters->get()->asArray(),
                [
                    'imageUploadError' => $this->translator->getLine(
                        'image_upload_error',
                    ),
                    'selectImageFromDevice' => $this->translator->getLine(
                        'select_image_from_device'
                    ),
                    'unusableImage' => $this->translator->getLine(
                        'unusable_image'
                    ),
                    'dimensionsNotMet' => $this
                        ->dimensionsNotMetTranslationFactory
                        ->get($fieldSettings),
                    'errorLoadingImage' => $this->translator->getLine(
                        'error_loading_image'
                    ),
                    'limitedToXImages' => $limitedToXImages,
                    'selectExistingImage' => $this->translator->getLine(
                        'select_existing_image'
                    ),
                    'dragImagesToUpload' => $this->translator->getLine(
                        'drag_images_to_upload'
                    ),
                    'or' => $this->translator->getLine('or'),
                    'removeImage' => $this->translator->getLine(
                        'remove_image'
                    ),
                    'editFields' => $this->translator->getLine(
                        'edit_fields'
                    ),
                    'editFocalPoint' => $this->translator->getLine(
                        'edit_focal_point'
                    ),
                    'editImage' => $this->translator->getLine(
                        'edit_image'
                    ),
                    'placeFocalPoint' => $this->translator->getLine(
                        'place_focal_point'
                    ),
                    'editImageCrop' => $this->translator->getLine(
                        'edit_image_crop'
                    ),
                    'fieldOverLimit' => $fieldOverLimit,
                    'fieldUnderLimit' => $fieldUnderLimit,
                ],
                $platform,
            ),
        ];
    }
}
