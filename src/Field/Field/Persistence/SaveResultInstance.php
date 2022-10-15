<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field\Persistence;

class SaveResultInstance implements SaveResult
{
    private bool $wasSuccessful;

    public function __construct(bool $wasSuccessful)
    {
        $this->wasSuccessful = $wasSuccessful;
    }

    public function wasSuccessful(): bool
    {
        return $this->wasSuccessful;
    }
}
