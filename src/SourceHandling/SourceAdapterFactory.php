<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\SourceHandling;

interface SourceAdapterFactory
{
    public function listAllSourceAdapters(
        bool $enabledOnly = true
    ): SourceAdapterListCollection;

    public function createInstanceByShortName(
        string $shortName,
        bool $enabledOnly = true
    ): AnselSourceAdapter;
}
