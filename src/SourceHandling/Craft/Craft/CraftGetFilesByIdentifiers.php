<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\SourceHandling\Craft\Craft;

use BuzzingPixel\Ansel\Shared\CraftAssetQueryBuilderFactory;
use BuzzingPixel\Ansel\SourceHandling\FileInstanceCollection;
use craft\elements\Asset;
use yii\base\InvalidConfigException;

use function array_filter;
use function array_map;
use function array_merge;
use function array_values;
use function count;
use function is_numeric;

class CraftGetFilesByIdentifiers
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
     * @param string[] $identifiers
     *
     * @throws InvalidConfigException
     */
    public function get(array $identifiers): FileInstanceCollection
    {
        $numericIdentifiers = array_values(array_filter(
            $identifiers,
            static fn ($id) => is_numeric($id),
        ));

        $nonNumericIdentifiers = array_values(array_filter(
            $identifiers,
            static fn ($id) => ! is_numeric($id),
        ));

        /** @var Asset[] $assets */
        $assets = [];

        if (count($numericIdentifiers) > 0) {
            $assets = array_merge(
                $assets,
                $this->assetQueryBuilderFactory->create()
                    ->id($numericIdentifiers)
                    ->all(),
            );
        }

        if (count($nonNumericIdentifiers) > 0) {
            $assets = array_merge(
                $assets,
                $this->assetQueryBuilderFactory->create()
                    ->uid($nonNumericIdentifiers)
                    ->all(),
            );
        }

        return new FileInstanceCollection(array_map(
            /**
             * @throws InvalidConfigException
             */
            fn (Asset $asset) => $this->createFileInstanceFromFileElement->create(
                $asset,
            ),
            $assets,
        ));
    }
}
