<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field;

class ValidatedFieldError
{
    private string $errorMsg;

    public function __construct(string $errorMsg)
    {
        $this->errorMsg = $errorMsg;
    }

    public function errorMsg(): string
    {
        return $this->errorMsg;
    }
}
