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

        $limitedToXImages = $maxQty > 1 ?
            $this->translator
                ->getLineWithReplacements(
                    'limited_to_x_images',
                    [
                        '{{qty}}' => (string) $maxQty,
                    ]
                ) :
            $this->translator->getLine(
                'limited_to_1_image',
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
                ],
                $platform,
            ),
        ];
    }
}
