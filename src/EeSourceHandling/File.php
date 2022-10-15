<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\EeSourceHandling;

interface File
{
    /**
     * @return class-string<AnselSourceAdapter>
     */
    public function sourceAdapterClass(): string;

    /**
     * The files primary identifier
     */
    public function identifier(): string;

    /**
     * The identifier for the source where this file comes from
     */
    public function sourceIdentifier(): string;

    /**
     * The full path to the file on disk
     *
     * If the file is on a remote server, this should be null
     */
    public function path(): ?string;

    /**
     * The URL for the file
     */
    public function url(): string;

    /**
     * The path if not null, otherwise the URL
     */
    public function pathOrUrl(): string;

    /**
     * The name of the file INCLUDING the extension
     */
    public function baseName(): string;

    /**
     * The name of the file WITHOUT the extension
     */
    public function fileName(): string;

    /**
     * The file's extension (without the dot)
     */
    public function fileNameExtension(): string;

    /**
     * The file size in bytes
     */
    public function filesize(): int;

    /**
     * Width of image
     */
    public function width(): int;

    /**
     * Height of image
     */
    public function height(): int;
}
