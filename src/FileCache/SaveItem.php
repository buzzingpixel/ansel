<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\FileCache;

use BuzzingPixel\Ansel\Shared\EE\SiteMeta;
use BuzzingPixel\Ansel\Shared\Php\InternalFunctions;
use BuzzingPixel\Ansel\Shared\Php\Server;
use BuzzingPixel\AnselConfig\Paths;
use SplFileInfo;
use Throwable;

use function explode;
use function file_get_contents;
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

    public function save(
        FileCacheItem $item,
        CacheDirectory $directory
    ): bool {
        $content = '';

        if ($item->getFilePathOrUrl() !== null) {
            $content = $this->getFileContents($item->getFilePathOrUrl());
        } elseif ($item->getFileContent() !== null) {
            $content = $item->getFileContent();
        }

        $keyParts = explode('/', $item->getKey());

        $id = $keyParts[0];

        $fileName = $keyParts[1];

        $dirPath = $directory->getPath($this->paths);

        $idPath = $dirPath . '/' . $id;

        $metaPath = $dirPath . '/' . $id . '/meta.json';

        $fullPath = $idPath . '/' . $fileName;

        $this->functions->mkdir($idPath);

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

        $this->functions->filePutContents(
            $fullPath,
            $content
        );

        $item->set(new SplFileInfo($fullPath));

        return true;
    }

    private function getFileContents(string $pathOrUrl): string
    {
        // Try the file as is and return it if applicable
        $content = $this->tryFileGetContents($pathOrUrl);

        if ($content !== '') {
            return $content;
        }

        // end

        // Try with a url safe filename
        $pathOrUrlArray = explode('/', $pathOrUrl);
        foreach ($pathOrUrlArray as $key => $val) {
            if (strpos($val, 'http') !== false) {
                continue;
            }

            $pathOrUrlArray[$key] = rawurlencode($val);
        }

        $pathOrUrlSafe = implode('/', $pathOrUrlArray);

        $content = $this->tryFileGetContents($pathOrUrlSafe);

        if ($content !== '') {
            return $content;
        }

        // end

        // Try with the configured site URL
        $siteUrl = $this->siteMeta->frontEndUrl();

        $content = $this->tryFileGetContents(
            $siteUrl . $pathOrUrl
        );

        if ($content !== '') {
            return $content;
        }

        // end

        // Try adding a slash after configured site URL
        $siteUrl = $this->siteMeta->frontEndUrl();

        $content = $this->tryFileGetContents(
            $siteUrl . '/' . $pathOrUrl
        );

        if ($content !== '') {
            return $content;
        }

        // end

        // Try with the configured site URL and URL Safe path
        $siteUrl = $this->siteMeta->frontEndUrl();

        $content = $this->tryFileGetContents(
            $siteUrl . $pathOrUrlSafe
        );

        if ($content !== '') {
            return $content;
        }

        // end

        // Try adding a slash after configured site URL and URL Safe path
        $siteUrl = $this->siteMeta->frontEndUrl();

        $content = $this->tryFileGetContents(
            $siteUrl . '/' . $pathOrUrlSafe
        );

        if ($content !== '') {
            return $content;
        }

        // end

        // Try with server site URL
        $siteUrl = $this->server->serverSiteUrl();

        $content = $this->tryFileGetContents(
            $siteUrl . $pathOrUrl
        );

        if ($content !== '') {
            return $content;
        }

        // end

        // Try adding a slash after server site URL
        $siteUrl = $this->siteMeta->frontEndUrl();

        $content = $this->tryFileGetContents(
            $siteUrl . '/' . $pathOrUrl
        );

        if ($content !== '') {
            return $content;
        }

        // end

        // Try with server site URL and URL Safe path
        $siteUrl = $this->siteMeta->frontEndUrl();

        $content = $this->tryFileGetContents(
            $siteUrl . $pathOrUrlSafe
        );

        if ($content !== '') {
            return $content;
        }

        // end

        // Last hurrah

        // Try adding a slash after server site URL and URL Safe path
        $siteUrl = $this->siteMeta->frontEndUrl();

        return $this->tryFileGetContents(
            $siteUrl . '/' . $pathOrUrlSafe
        );
    }

    private function tryFileGetContents(string $pathOrUrl): string
    {
        $context = $this->functions->streamContextCreate([
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
            ],
        ]);

        try {
            $content = file_get_contents(
                $pathOrUrl,
                false,
                $context,
            );

            if ($content !== '' && $content !== false) {
                return $content;
            }
        } catch (Throwable $e) {
        }

        return '';
    }
}
