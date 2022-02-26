<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Shared\EE;

class SiteMeta
{
    private int $siteId;

    public function __construct(int $siteId)
    {
        $this->siteId = $siteId;
    }

    public function siteId(): int
    {
        return $this->siteId;
    }
}
