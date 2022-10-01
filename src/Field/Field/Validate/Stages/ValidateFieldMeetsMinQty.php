<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field\Validate\Stages;

use BuzzingPixel\Ansel\Field\Field\Validate\ValidateFieldPayload;
use BuzzingPixel\Ansel\Translations\TranslatorContract;

class ValidateFieldMeetsMinQty implements ValidationStage
{
    private TranslatorContract $translator;

    public function __construct(TranslatorContract $translator)
    {
        $this->translator = $translator;
    }

    public function __invoke(
        ValidateFieldPayload $payload
    ): ValidateFieldPayload {
        $count = $payload->data->postedImages()->count();

        $minQty = $payload->settings->minQty()->value();

        // If count meets requirements we can bail out
        if ($count >= $minQty) {
            return $payload;
        }

        // We know now that we do not meet minimum requirements

        if ($minQty === 1) {
            $msg = $this->translator->getLine('must_add_1_image');
        } else {
            $msg = $this->translator->getLineWithReplacements(
                'must_add_qty_images',
                ['{{qty}}' => (string) $minQty]
            );
        }

        $payload->validatedFieldResult->addErrorMessage($msg);

        return $payload;
    }
}
