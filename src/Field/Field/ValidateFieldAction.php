<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field;

use BuzzingPixel\Ansel\Field\Field\PostedFieldData\PostedData;
use BuzzingPixel\Ansel\Field\Settings\FieldSettingsCollection;
use BuzzingPixel\Ansel\Translations\TranslatorContract;

class ValidateFieldAction
{
    private TranslatorContract $translator;

    public function __construct(TranslatorContract $translator)
    {
        $this->translator = $translator;
    }

    public function validate(
        FieldSettingsCollection $settings,
        PostedData $data
    ): ValidatedFieldResult {
        $errors = [];

        $count = $data->postedImages()->count();

        $minQty = $settings->minQty()->value();

        // Make sure we have the minimum specified images
        if ($count < $minQty) {
            if ($minQty === 1) {
                $msg = $this->translator->getLine('must_add_1_image');
            } else {
                $msg = $this->translator->getLineWithReplacements(
                    'must_add_qty_images',
                    ['{{qty}}' => (string) $minQty]
                );
            }

            $errors[] = new ValidatedFieldError($msg);
        }

        // TODO: further validation

        return new ValidatedFieldResult($errors);
    }
}
