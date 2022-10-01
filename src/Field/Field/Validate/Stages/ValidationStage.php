<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field\Validate\Stages;

use BuzzingPixel\Ansel\Field\Field\Validate\ValidateFieldPayload;

interface ValidationStage
{
    public function __invoke(
        ValidateFieldPayload $payload
    ): ValidateFieldPayload;
}
