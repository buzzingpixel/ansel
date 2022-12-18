<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\SourceHandling;

interface StorageLocation
{
    public function id(): string;

    public function displayName(): string;
}
