<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\UpdatesFeed;

use DateTimeImmutable;

class UpdateItem
{
    private bool $isNew;

    private string $version;

    private string $downloadUrl;

    private DateTimeImmutable $date;

    private string $notes;

    public function __construct(
        bool $isNew,
        string $version,
        string $downloadUrl,
        DateTimeImmutable $date,
        string $notes
    ) {
        $this->isNew       = $isNew;
        $this->version     = $version;
        $this->downloadUrl = $downloadUrl;
        $this->date        = $date;
        $this->notes       = $notes;
    }

    public function isNew(): bool
    {
        return $this->isNew;
    }

    public function version(): string
    {
        return $this->version;
    }

    public function downloadUrl(): string
    {
        return $this->downloadUrl;
    }

    public function date(): DateTimeImmutable
    {
        return $this->date;
    }

    public function notes(): string
    {
        return $this->notes;
    }
}
