<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Settings\ExpressionEngine;

use BuzzingPixel\Ansel\Field\Settings\FieldSettingsCollectionValidatorContract;
use ExpressionEngine\Service\Validation\Result;

class ValidateSettings
{
    private FieldSettingsFromRaw $fromRaw;

    private FieldSettingsCollectionValidatorContract $validator;

    public function __construct(
        FieldSettingsFromRaw $fromRaw,
        FieldSettingsCollectionValidatorContract $validator
    ) {
        $this->fromRaw   = $fromRaw;
        $this->validator = $validator;
    }

    /**
     * @param mixed[] $data
     */
    public function validate(array $data): Result
    {
        $errors = $this->validator->validate(
            $this->fromRaw->get(
                $data
            ),
        );

        $result = new Result();

        foreach ($errors as $errorKey => $error) {
            /** @phpstan-ignore-next-line */
            $result->addFailed($errorKey, $error);
        }

        return $result;
    }
}
