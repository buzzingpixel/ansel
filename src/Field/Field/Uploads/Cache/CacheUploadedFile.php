<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field\Uploads\Cache;

use BuzzingPixel\Ansel\Shared\ClockContract;
use BuzzingPixel\Ansel\Shared\Php\InternalFunctions;
use BuzzingPixel\Ansel\Translations\TranslatorContract;
use BuzzingPixel\AnselConfig\Paths;
use Cocur\Slugify\Slugify;
use DateInterval;
use Psr\Http\Message\UploadedFileInterface;
use Ramsey\Uuid\UuidFactoryInterface;
use Throwable;

use function base64_encode;
use function implode;
use function json_encode;
use function pathinfo;

use const PATHINFO_EXTENSION;

class CacheUploadedFile
{
    private Paths $paths;

    private Slugify $slugify;

    private ClockContract $clock;

    private TranslatorContract $translator;

    private UuidFactoryInterface $uuidFactory;

    private GarbageCollection $garbageCollection;

    private InternalFunctions $internalFunctions;

    public function __construct(
        Paths $paths,
        Slugify $slugify,
        ClockContract $clock,
        TranslatorContract $translator,
        UuidFactoryInterface $uuidFactory,
        GarbageCollection $garbageCollection,
        InternalFunctions $internalFunctions
    ) {
        $this->paths             = $paths;
        $this->slugify           = $slugify;
        $this->clock             = $clock;
        $this->translator        = $translator;
        $this->uuidFactory       = $uuidFactory;
        $this->garbageCollection = $garbageCollection;
        $this->internalFunctions = $internalFunctions;
    }

    public function cache(
        UploadedFileInterface $uploadedFile
    ): CacheUploadedFileResult {
        try {
            $this->garbageCollection->run();

            return $this->internalCache($uploadedFile);
        } catch (Throwable $exception) {
            return new CacheUploadedFileResult(
                false,
                $this->translator->getLine(
                    'image_upload_error',
                ),
            );
        }
    }

    private function internalCache(
        UploadedFileInterface $uploadedFile
    ): CacheUploadedFileResult {
        $uuid = $this->uuidFactory->uuid4()->toString();

        $name = (string) $uploadedFile->getClientFilename();

        $nameInfo = pathinfo($name);

        $type = pathinfo($name, PATHINFO_EXTENSION);

        $originalName = $nameInfo['filename'];

        if ($originalName === '') {
            $originalName = $uuid;
        }

        $extension = '.' . ($nameInfo['extension'] ?? 'tmp');

        $cleanName = $this->slugify->slugify($originalName) . $extension;

        $cacheDirectory = implode('/', [
            $this->paths->anselCachePath(),
            $uuid,
        ]);

        $this->internalFunctions->mkdir($cacheDirectory);

        $cacheFilePath = implode('/', [
            $cacheDirectory,
            $cleanName,
        ]);

        $cacheMetaPath = implode('/', [
            $cacheDirectory,
            'meta.json',
        ]);

        $uploadedFile->moveTo($cacheFilePath);

        $now = $this->clock->now();

        $nowPlus24 = $now->add(new DateInterval('P1D'));

        $this->internalFunctions->filePutContents(
            $cacheMetaPath,
            (string) json_encode([
                'time' => $now->getTimestamp(),
                'expires' => $nowPlus24->getTimestamp(),
            ]),
        );

        $fileContents = $this->internalFunctions->fileGetContents(
            $cacheFilePath,
        );

        return new CacheUploadedFileResult(
            true,
            '',
            $name,
            $cacheDirectory,
            $cacheFilePath,
            'data:image/' . $type . ';base64,' . base64_encode(
                $fileContents,
            ),
        );
    }
}
