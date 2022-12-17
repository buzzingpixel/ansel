<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\FileCache;

use BuzzingPixel\Ansel\Shared\Php\InternalFunctions;
use BuzzingPixel\Ansel\Shared\Php\Server;
use BuzzingPixel\Ansel\Shared\SiteMeta;
use BuzzingPixel\AnselConfig\Paths;
use Exception;
use SplFileInfo;
use Throwable;

use function copy;
use function explode;
use function implode;
use function json_encode;
use function rawurlencode;
use function strpos;

class SaveItem
{
    private Paths $paths;

    private Server $server;

    private SiteMeta $siteMeta;

    private InternalFunctions $functions;

    public function __construct(
        Paths $paths,
        Server $server,
        SiteMeta $siteMeta,
        InternalFunctions $functions
    ) {
        $this->paths     = $paths;
        $this->server    = $server;
        $this->siteMeta  = $siteMeta;
        $this->functions = $functions;
    }

    /**
     * @throws Exception
     */
    public function save(
        FileCacheItem $item,
        CacheDirectory $directory
    ): bool {
        $keyParts = explode('/', $item->getKey());

        $id = $keyParts[0];

        $fileName = $keyParts[1];

        $dirPath = $directory->getPath($this->paths);

        $idPath = $dirPath . '/' . $id;

        $metaPath = $dirPath . '/' . $id . '/meta.json';

        $fullPath = $idPath . '/' . $fileName;

        $this->functions->mkdirIfNotExists($idPath);

        $expiresAt = $item->getExpiresAt();

        $this->functions->filePutContents(
            $metaPath,
            (string) json_encode([
                'time' => $item->getCreatedAt()->getTimestamp(),
                'expires' => $expiresAt !== null ?
                    $expiresAt->getTimestamp() :
                    null,
            ]),
        );

        if ($item->getFileContent() !== null) {
            $this->functions->filePutContents(
                $fullPath,
                $item->getFileContent(),
            );

            return $this->finish($item, $fullPath);
        }

        if ($item->getFilePathOrUrl() !== null) {
            $this->copyFile(
                $item->getFilePathOrUrl(),
                $fullPath,
            );

            return $this->finish($item, $fullPath);
        }

        $this->functions->filePutContents($fullPath, '');

        return $this->finish($item, $fullPath);
    }

    private function finish(
        FileCacheItem $item,
        string $fullPath
    ): bool {
        $item->set(new SplFileInfo($fullPath));

        $item->setFileContent('');

        $item->setFilePathOrUrl('');

        return true;
    }

    /**
     * @throws Exception
     */
    private function copyFile(string $sourcePathOrUrl, string $destPath): void
    {
        // Try to copy the file as is and return it if applicable
        $result = $this->tryCopy(
            $sourcePathOrUrl,
            $destPath
        );

        if ($result) {
            return;
        }

        // end try

        // Try with an url safe filename
        $sourcePathOrUrlArray = explode('/', $sourcePathOrUrl);
        foreach ($sourcePathOrUrlArray as $key => $val) {
            if (strpos($val, 'http') !== false) {
                continue;
            }

            $sourcePathOrUrlArray[$key] = rawurlencode($val);
        }

        $sourcePathOrUrlSafe = implode(
            '/',
            $sourcePathOrUrlArray,
        );

        $result = $this->tryCopy(
            $sourcePathOrUrlSafe,
            $destPath
        );

        if ($result) {
            return;
        }

        // end try

        // Try with the configured site URL
        $siteUrl = $this->siteMeta->frontEndUrl();

        $result = $this->tryCopy(
            $siteUrl . $sourcePathOrUrl,
            $destPath
        );

        if ($result) {
            return;
        }

        // end try

        // Try adding a slash after configured site URL
        $result = $this->tryCopy(
            $siteUrl . '/' . $sourcePathOrUrl,
            $destPath
        );

        if ($result) {
            return;
        }

        // end try

        // Try with the configured site URL and URL Safe path
        $result = $this->tryCopy(
            $siteUrl . $sourcePathOrUrlSafe,
            $destPath
        );

        if ($result) {
            return;
        }

        // end try

        // Try adding a slash after configured site URL and URL Safe path
        $result = $this->tryCopy(
            $siteUrl . '/' . $sourcePathOrUrlSafe,
            $destPath
        );

        if ($result) {
            return;
        }

        // end try

        // Try with server site URL
        $siteUrl = $this->server->serverSiteUrl();

        $result = $this->tryCopy(
            $siteUrl . $sourcePathOrUrl,
            $destPath
        );

        if ($result) {
            return;
        }

        // end try

        // Try adding a slash after server site URL
        $result = $this->tryCopy(
            $siteUrl . '/' . $sourcePathOrUrl,
            $destPath
        );

        if ($result) {
            return;
        }

        // end try

        // Try with server site URL and URL Safe path
        $result = $this->tryCopy(
            $siteUrl . $sourcePathOrUrlSafe,
            $destPath
        );

        if ($result) {
            return;
        }

        // end try

        // Try adding a slash after server site URL and URL Safe path
        $result = $this->tryCopy(
            $siteUrl . '/' . $sourcePathOrUrlSafe,
            $destPath
        );

        if ($result) {
            return;
        }

        throw new Exception('Unable to create cache file');
    }

    private function tryCopy(string $sourcePathOrUrl, string $destPath): bool
    {
        $context = $this->functions->streamContextCreate([
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
            ],
        ]);

        try {
            /**
             * Hate using the @ symbol but this older PHP function leaves us
             * little choice
             */
            return @copy(
                $sourcePathOrUrl,
                $destPath,
                $context,
            );
        } catch (Throwable $exception) {
            return false;
        }
    }
}
