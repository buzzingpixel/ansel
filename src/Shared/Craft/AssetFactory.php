<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Shared\Craft;

use craft\elements\Asset;

class AssetFactory
{
    public function create(): Asset
    {
        return new Asset();
    }
}
