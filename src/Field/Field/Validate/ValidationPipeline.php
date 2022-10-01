<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field\Validate;

use League\Pipeline\Pipeline;

// phpcs:disable SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint

class ValidationPipeline extends Pipeline
{
    public function __construct(callable ...$stages)
    {
        parent::__construct(null, ...$stages);
    }

    /**
     * We're just doing this for the typehints
     *
     * @param ValidateFieldPayload $payload
     *
     * @phpstan-ignore-next-line
     */
    public function process($payload): ValidateFieldPayload
    {
        return parent::process($payload);
    }
}
