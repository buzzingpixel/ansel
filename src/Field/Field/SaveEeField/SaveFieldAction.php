<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field\SaveEeField;

use BuzzingPixel\Ansel\EeSourceHandling\SourceAdapterFactory;
use BuzzingPixel\Ansel\Field\Field\Persistence\AnselFieldEeRecord;
use BuzzingPixel\Ansel\Field\Field\Persistence\AnselImageEeRecord;
use BuzzingPixel\Ansel\Field\Field\Persistence\RecordCollection;
use BuzzingPixel\Ansel\Field\Field\Persistence\RecordService;
use BuzzingPixel\Ansel\Field\Field\PostedFieldData\PostedFieldData;
use BuzzingPixel\Ansel\Field\Field\PostedFieldData\PostedImage;
use BuzzingPixel\Ansel\FileCache\EphemeralFileCachePool;
use BuzzingPixel\Ansel\ImageManipulation\Manipulator;
use BuzzingPixel\Ansel\ImageManipulation\OutputType;
use BuzzingPixel\Ansel\ImageManipulation\Parameters;
use BuzzingPixel\Ansel\Shared\EE\SiteMeta;
use EE_Session;
use Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

use function array_walk_recursive;
use function assert;
use function is_int;
use function is_string;

class SaveFieldAction
{
    private SiteMeta $siteMeta;

    private EE_Session $eeSession;

    private CreateField $createField;

    private RecordService $recordService;

    private Manipulator $imageManipulator;

    private SourceAdapterFactory $sourceFactory;

    private CreateNewImageRecord $createNewRecord;

    private EphemeralFileCachePool $ephemeralFileCachePool;

    public function __construct(
        SiteMeta $siteMeta,
        EE_Session $eeSession,
        CreateField $createField,
        RecordService $recordService,
        Manipulator $imageManipulator,
        SourceAdapterFactory $sourceFactory,
        CreateNewImageRecord $createNewImageRecord,
        EphemeralFileCachePool $ephemeralFileCachePool
    ) {
        $this->siteMeta               = $siteMeta;
        $this->eeSession              = $eeSession;
        $this->createField            = $createField;
        $this->recordService          = $recordService;
        $this->imageManipulator       = $imageManipulator;
        $this->sourceFactory          = $sourceFactory;
        $this->createNewRecord        = $createNewImageRecord;
        $this->ephemeralFileCachePool = $ephemeralFileCachePool;
    }

    public function save(Payload $payload): void
    {
        $imageRecords = $payload->data()->postedImages()->map(
            function (
                PostedImage $image,
                int $index
            ) use ($payload): AnselImageEeRecord {
                return $this->createImageRecord(
                    $image,
                    $payload,
                    $index,
                );
            }
        );

        $fieldRecordsIntermediate = $payload->data()->postedImages()->map(
            function (PostedImage $image): array {
                return $image->postedFieldDataCollection()->map(
                    function (
                        PostedFieldData $fieldData
                    ) use (
                        $image
                    ): AnselFieldEeRecord {
                        return $this->createField->create(
                            $image,
                            $fieldData
                        );
                    }
                );
            }
        );

        // Flatten array
        $fieldRecords = [];
        array_walk_recursive(
            $fieldRecordsIntermediate,
            static function ($a) use (&$fieldRecords): void {
                $fieldRecords[] = $a;
            }
        );

        $this->recordService->saveRecords(
            new RecordCollection($imageRecords),
        );

        $this->recordService->saveRecords(
            new RecordCollection($fieldRecords),
        );
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws Exception
     */
    private function createImageRecord(
        PostedImage $image,
        Payload $payload,
        int $index
    ): AnselImageEeRecord {
        $maxWidth  = $payload->fieldSettings()->maxWidth()->value();
        $maxHeight = $payload->fieldSettings()->maxWidth()->value();

        // TODO: use cached image
        // TODO: check for existing image using source id and content ID etc.
        // TODO: Logic to determine if we're saving new, updating, or duplicating
        // TODO: Upload source file to source directory

        $uploadSourceAdapter = $this->sourceFactory->createInstanceByShortName(
            $payload->fieldSettings()
                ->uploadLocation()
                ->directoryType(),
        );

        $sourceFile = $uploadSourceAdapter->getFileByIdentifier(
            $image->sourceImageId()
        );

        if ($sourceFile === null) {
            throw new Exception(
                'Source file not found (this should not be possible).'
            );
        }

        $cachedSourceFile = $this->ephemeralFileCachePool->createItem(
            $sourceFile->baseName(),
        )->setFilePathOrUrl($sourceFile->pathOrUrl());

        $this->ephemeralFileCachePool->save($cachedSourceFile);

        $manipulatedCacheFile = $this->ephemeralFileCachePool->createItem(
            $sourceFile->baseName(),
        );

        $this->ephemeralFileCachePool->save($manipulatedCacheFile);

        $this->imageManipulator->runManipulation(
            $cachedSourceFile->get(),
            new Parameters(
                (int) $image->x(),
                (int) $image->y(),
                (int) $image->width(),
                (int) $image->height(),
                null,
                null,
                $maxWidth > 0 ? $maxWidth : null,
                $maxHeight > 0 ? $maxHeight : null,
                $payload->fieldSettings()->forceJpg()->value() ?
                    OutputType::JPG() :
                    null,
                $payload->fieldSettings()->quality()->value(),
            ),
            $manipulatedCacheFile->get()->getPath(),
            $manipulatedCacheFile->get()->getBasename(
                '.' . $manipulatedCacheFile->get()->getExtension(),
            ),
        );

        $this->ephemeralFileCachePool->deleteItem(
            $cachedSourceFile->getKey(),
        );

        $saveSourceAdapter = $this->sourceFactory->createInstanceByShortName(
            $payload->fieldSettings()
                ->saveLocation()
                ->directoryType(),
        );

        $memberId = $this->eeSession->userdata('member_id');

        assert(is_int($memberId) || is_string($memberId));

        $savedFile = $saveSourceAdapter->addFile(
            $payload->fieldSettings()
                ->saveLocation()
                ->directoryId(),
            $manipulatedCacheFile->get(),
            (string) $memberId,
        );

        $this->ephemeralFileCachePool->deleteItem(
            $manipulatedCacheFile->getKey(),
        );

        return $this->createNewRecord->create(
            $image,
            $payload,
            $index,
            $sourceFile,
            $savedFile,
            $this->siteMeta,
            (int) $memberId,
        );
    }
}
