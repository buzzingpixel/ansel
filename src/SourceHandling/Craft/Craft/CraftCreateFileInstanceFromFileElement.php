<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\SourceHandling\Craft\Craft;

use BuzzingPixel\Ansel\SourceHandling\Craft\CraftSourceAdapter;
use BuzzingPixel\Ansel\SourceHandling\FileInstance;
use craft\elements\Asset;
use yii\base\InvalidConfigException;

use function implode;
use function method_exists;

class CraftCreateFileInstanceFromFileElement
{
    /**
     * @throws InvalidConfigException
     */
    public function create(Asset $asset): FileInstance
    {
        $volume = $asset->getVolume();

        $path = [];

        if (method_exists($volume, 'getRootPath')) {
            $path[] = $volume->getRootPath();
        }

        if ($asset->folderPath !== null && $asset->folderPath !== '') {
            $path[] = $asset->folderPath;
        }

        $path[] = $asset->getPath();

        return new FileInstance(
            CraftSourceAdapter::class,
            (string) $asset->uid,
            (string) $volume->uid,
            implode('/', $path),
            (string) $asset->getUrl(),
            (int) $asset->size,
            (int) $asset->getWidth(),
            (int) $asset->getHeight(),
        );
    }
}
