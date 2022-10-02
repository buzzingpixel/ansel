<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field\Validate\Stages;

use BuzzingPixel\Ansel\Field\Field\Validate\ValidateFieldPayload;
use BuzzingPixel\Ansel\Translations\TranslatorContract;

/**
 * JS should take care of this for us, but we should "drive" defensively
 */
class ValidateFieldNotOverMaxQty implements ValidationStage
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

        $maxQty = $payload->settings->maxQty()->value();

        // If there is no maxQty, or we're under, we can bail out
        if (
            ! $payload->settings->preventUploadOverMax()->value() ||
            $maxQty < 1 ||
            $count <= $maxQty
        ) {
            return $payload;
        }

        // We know now that we are over requirements

        if ($maxQty === 1) {
            $msg = $this->translator->getLine(
                'must_not_add_more_than_1_image'
            );
        } else {
            $msg = $this->translator->getLineWithReplacements(
                'must_not_add_more_than_x_images',
                ['{{qty}}' => (string) $maxQty]
            );
        }

        $payload->validatedFieldResult->addErrorMessage($msg);

        return $payload;
    }
}
