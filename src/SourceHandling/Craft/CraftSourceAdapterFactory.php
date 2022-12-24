<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\SourceHandling\Craft;

use BuzzingPixel\Ansel\SourceHandling\AnselSourceAdapter;
use BuzzingPixel\Ansel\SourceHandling\SourceAdapterFactory;
use BuzzingPixel\Ansel\SourceHandling\SourceAdapterListCollection;
use BuzzingPixel\Ansel\SourceHandling\SourceAdapterListItem;
use InvalidArgumentException;

class CraftSourceAdapterFactory implements SourceAdapterFactory
{
    private CraftSourceAdapter $craftSourceAdapter;

    public function __construct(CraftSourceAdapter $craftSourceAdapter)
    {
        $this->craftSourceAdapter = $craftSourceAdapter;
    }

    /**
     * On Craft, we only support the native option(s)
     */
    public function listAllSourceAdapters(
        bool $enabledOnly = true
    ): SourceAdapterListCollection {
        return new SourceAdapterListCollection([
            new SourceAdapterListItem(
                CraftSourceAdapter::getShortName(),
                CraftSourceAdapter::getDisplayName(),
                CraftSourceAdapter::class,
            ),
        ]);
    }

    public function createInstanceByShortName(
        string $shortName,
        bool $enabledOnly = true
    ): AnselSourceAdapter {
        if ($shortName !== 'craft') {
            throw new InvalidArgumentException(
                $shortName . ' is not a valid AnselSourceAdapter'
            );
        }

        return $this->craftSourceAdapter;
    }
}
