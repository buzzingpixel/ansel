<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\SourceHandling\Craft\Craft;

use BuzzingPixel\Ansel\Shared\Craft\AssetFactory;
use BuzzingPixel\Ansel\Shared\Facades\CraftAssetsHelper;
use BuzzingPixel\Ansel\Shared\Php\InternalFunctions;
use BuzzingPixel\Ansel\SourceHandling\Craft\CraftSourceAdapter;
use BuzzingPixel\Ansel\SourceHandling\File;
use BuzzingPixel\Ansel\SourceHandling\FileInstance;
use BuzzingPixel\AnselConfig\Paths;
use craft\base\Volume;
use craft\elements\Asset;
use craft\errors\ElementNotFoundException;
use craft\models\VolumeFolder;
use craft\services\Assets;
use craft\services\Elements as ElementsService;
use craft\services\Volumes;
use RuntimeException;
use SplFileInfo;
use Throwable;
use yii\base\Exception;

use function implode;
use function is_numeric;
use function method_exists;

class CraftAddFile
{
    private Paths $paths;

    private Assets $assets;

    private Volumes $volumes;

    private AssetFactory $assetFactory;

    private CraftAssetsHelper $assetsHelper;

    private ElementsService $elementsService;

    private InternalFunctions $internalFunctions;

    public function __construct(
        Paths $paths,
        Assets $assets,
        Volumes $volumes,
        AssetFactory $assetFactory,
        CraftAssetsHelper $assetsHelper,
        ElementsService $elementsService,
        InternalFunctions $internalFunctions
    ) {
        $this->paths             = $paths;
        $this->assets            = $assets;
        $this->volumes           = $volumes;
        $this->assetFactory      = $assetFactory;
        $this->assetsHelper      = $assetsHelper;
        $this->elementsService   = $elementsService;
        $this->internalFunctions = $internalFunctions;
    }

    /**
     * @throws Exception
     * @throws Throwable
     * @throws ElementNotFoundException
     */
    public function add(
        string $locationIdentifier,
        SplFileInfo $file,
        string $memberId = ''
    ): File {
        /**
         * Unfortunately Craft moves files when saving a new asset because Craft
         * sucks so much sometimes. So we'll copy the file to system cache so
         * that we don't remove a file we don't know anything about
         */
        $tmpPath = $this->paths->systemCachePath() . '/' . $file->getBasename();

        $this->internalFunctions->copy(
            $file->getPathname(),
            $tmpPath,
        );

        if (is_numeric($locationIdentifier)) {
            $volume = $this->volumes->getVolumeById(
                (int) $locationIdentifier,
            );
        } else {
            $volume = $this->volumes->getVolumeByUid(
                $locationIdentifier,
            );
        }

        if (! ($volume instanceof Volume)) {
            throw new RuntimeException('Invalid volume identifier');
        }

        $folder = $this->assets->findFolder([
            'name' => $volume->name,
            'volumeId' => $volume->id,
        ]);

        if (! ($folder instanceof VolumeFolder)) {
            throw new RuntimeException('Invalid volume identifier');
        }

        $asset = $this->assetFactory->create();

        $asset->tempFilePath = $tmpPath;

        $asset->filename = $this->assetsHelper->prepareAssetName(
            $file->getBasename()
        );

        $asset->newFolderId = $folder->id;

        $asset->setVolumeId((int) $volume->id);

        $asset->avoidFilenameConflicts = true;

        $asset->setScenario(Asset::SCENARIO_CREATE);

        $status = $this->elementsService->saveElement($asset);

        if (! $status) {
            throw new RuntimeException(
                'There was a problem saving the asset',
            );
        }

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
