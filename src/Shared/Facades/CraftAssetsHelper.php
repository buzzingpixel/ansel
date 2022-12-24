<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Shared\Facades;

use craft\helpers\Assets as AssetsHelper;

class CraftAssetsHelper
{
    public function prepareAssetName(
        string $name,
        bool $isFilename = true,
        bool $preventPluginModifications = false
    ): string {
        return AssetsHelper::prepareAssetName(
            $name,
            $isFilename,
            $preventPluginModifications,
        );
    }
}
