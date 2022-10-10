<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\EeSourceHandling;

class StorageLocationItem implements StorageLocation
{
    private string $id;

    private string $displayName;

    public function __construct(string $id, string $displayName)
    {
        $this->id          = $id;
        $this->displayName = $displayName;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function displayName(): string
    {
        return $this->displayName;
    }
}
