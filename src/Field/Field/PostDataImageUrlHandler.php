<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field;

use BuzzingPixel\Ansel\FileCache\EphemeralFileCachePool;
use BuzzingPixel\Ansel\FileCache\FileCacheItem;
use DateInterval;

use function array_map;
use function is_array;
use function is_string;
use function json_decode;
use function json_encode;

class PostDataImageUrlHandler
{
    private EphemeralFileCachePool $fileCache;

    public function __construct(
        EphemeralFileCachePool $fileCache
    ) {
        $this->fileCache = $fileCache;
    }

    /**
     * @param mixed[] $value
     *
     * @return mixed[]
     *
     * @noinspection PhpDocMissingThrowsInspection
     */
    public function restoreFromCache(array $value): array
    {
        if (! isset($value['images']) || ! is_array($value['images'])) {
            return $value;
        }

        $value['images'] = array_map(
            function ($json) {
                if (! is_string($json)) {
                    return $json;
                }

                $imageData = json_decode($json, true);

                if (! is_array($imageData)) {
                    return $json;
                }

                $id  = $imageData['id'] . '-image-url';
                $id .= '/' . $id;

                /** @noinspection PhpUnhandledExceptionInspection */
                $cachedImageUrl = $this->fileCache->getItem($id);

                if (! $cachedImageUrl->isHit()) {
                    return $json;
                }

                $imageData['imageUrl'] = $cachedImageUrl->getCachedContent();

                return (string) json_encode($imageData);
            },
            $value['images'],
        );

        return $value;
    }

    /**
     * @param mixed $value
     *
     * @return mixed
     *
     * @noinspection PhpDocMissingThrowsInspection
     */
    public function scrub($value)
    {
        if (
            ! is_array($value) ||
            ! isset($value['images']) ||
            ! is_array($value['images'])
        ) {
            return $value;
        }

        $value['images'] = array_map(
            function ($json) {
                if (! is_string($json)) {
                    return $json;
                }

                $imageData = json_decode($json, true);

                if (
                    ! is_array($imageData) ||
                    ! isset($imageData['imageUrl'])
                ) {
                    return $json;
                }

                $id  = $imageData['id'] . '-image-url';
                $id .= '/' . $id;

                /** @noinspection PhpUnhandledExceptionInspection */
                $cacheItem = new FileCacheItem($id);

                $cacheItem->setFileContent($imageData['imageUrl']);

                /** @noinspection PhpUnhandledExceptionInspection */
                $cacheItem->expiresAfter(
                    new DateInterval('PT2M'),
                );

                /** @noinspection PhpUnhandledExceptionInspection */
                $this->fileCache->save($cacheItem);

                unset($imageData['imageUrl']);

                return (string) json_encode($imageData);
            },
            $value['images'],
        );

        return $value;
    }
}
