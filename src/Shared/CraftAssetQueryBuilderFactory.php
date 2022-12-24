<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Shared;

use craft\elements\Asset;
use craft\elements\db\AssetQuery;

class CraftAssetQueryBuilderFactory
{
    public function create(): AssetQuery
    {
        return Asset::find();
    }
}
