<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Shared;

class SiteMeta
{
    private int $siteId;

    private string $frontEndUrl;

    public function __construct(int $siteId, string $frontEndUrl)
    {
        $this->siteId      = $siteId;
        $this->frontEndUrl = $frontEndUrl;
    }

    public function siteId(): int
    {
        return $this->siteId;
    }

    public function frontEndUrl(): string
    {
        return $this->frontEndUrl;
    }
}
