<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\SourceHandling\Craft\Craft;

use BuzzingPixel\Ansel\Shared\CraftAssetQueryBuilderFactory;
use BuzzingPixel\Ansel\SourceHandling\FileInstance;
use craft\elements\Asset;
use yii\base\InvalidConfigException;

use function is_numeric;

class CraftGetFileByIdentifier
{
    private CraftAssetQueryBuilderFactory $assetQueryBuilderFactory;

    private CraftCreateFileInstanceFromFileElement $createFileInstanceFromFileElement;

    public function __construct(
        CraftAssetQueryBuilderFactory $assetQueryBuilderFactory,
        CraftCreateFileInstanceFromFileElement $createFileInstanceFromFileElement
    ) {
        $this->assetQueryBuilderFactory          = $assetQueryBuilderFactory;
        $this->createFileInstanceFromFileElement = $createFileInstanceFromFileElement;
    }

    /**
     * @throws InvalidConfigException
     */
    public function get(string $identifier): ?FileInstance
    {
        if (is_numeric($identifier)) {
            $asset = $this->assetQueryBuilderFactory->create()
                ->id($identifier)
                ->one();
        } else {
            $asset = $this->assetQueryBuilderFactory->create()
                ->uid($identifier)
                ->one();
        }

        if (! ($asset instanceof Asset)) {
            return null;
        }

        return $this->createFileInstanceFromFileElement->create($asset);
    }
}
