<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field\Persistence;

interface SaveResult
{
    public function wasSuccessful(): bool;
}
