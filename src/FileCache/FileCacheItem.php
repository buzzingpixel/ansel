<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\FileCache;

use DateInterval;
use DateTimeImmutable;
use DateTimeInterface;
use Exception;
use Psr\Cache\CacheItemInterface;
use SplFileInfo;

use function count;
use function explode;
use function is_int;

class FileCacheItem implements CacheItemInterface
{
    private string $key;

    private SplFileInfo $file;

    private DateTimeInterface $createdAt;

    private ?DateTimeInterface $expiresAt;

    private ?string $filePathOrUrl;

    private ?string $fileContent;

    /**
     * @throws InvalidArgument
     */
    public function __construct(
        string $key,
        ?SplFileInfo $file = null,
        ?DateTimeInterface $createdAt = null,
        ?DateTimeInterface $expiresAt = null,
        ?string $filePathOrUrl = null,
        ?string $fileContent = null
    ) {
        $keyParts = explode('/', $key);

        if (count($keyParts) !== 2) {
            throw new InvalidArgument();
        }

        if ($createdAt === null) {
            $createdAt = new DateTimeImmutable();
        }

        if ($file === null) {
            $file = new SplFileInfo('tmp');
        }

        $this->key           = $key;
        $this->file          = $file;
        $this->createdAt     = $createdAt;
        $this->expiresAt     = $expiresAt;
        $this->filePathOrUrl = $filePathOrUrl;
        $this->fileContent   = $fileContent;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @return SplFileInfo
     *
     * @inheritDoc
     */
    public function get(): SplFileInfo
    {
        return $this->file;
    }

    public function isHit(): bool
    {
        return $this->file->isFile();
    }

    /**
     * @param SplFileInfo $value
     *
     * @inheritDoc
     *
     * @phpstan-ignore-next-line
     */
    public function set($value): self
    {
        $this->file = $value;

        return $this;
    }

    /**
     * @inheritDoc
     * @phpstan-ignore-next-line
     */
    public function expiresAt($expiration): self
    {
        $this->expiresAt = $expiration;

        return $this;
    }

    /**
     * @throws Exception
     *
     * @inheritDoc
     *
     * @phpstan-ignore-next-line
     */
    public function expiresAfter($time): self
    {
        if ($time instanceof DateInterval) {
            $this->expiresAt((new DateTimeImmutable())->add($time));
        }

        if (is_int($time)) {
            $this->expiresAt(
                (new DateTimeImmutable(
                    '+ ' . ((string) $time) . ' seconds',
                )),
            );
        }

        $this->expiresAt(null);

        return $this;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getExpiresAt(): ?DateTimeInterface
    {
        return $this->expiresAt;
    }

    public function setFilePathOrUrl(string $pathOrUrl): self
    {
        $this->filePathOrUrl = $pathOrUrl;

        return $this;
    }

    public function getFilePathOrUrl(): ?string
    {
        return $this->filePathOrUrl;
    }

    public function setFileContent(string $content): self
    {
        $this->fileContent = $content;

        return $this;
    }

    public function getFileContent(): ?string
    {
        return $this->fileContent;
    }
}
