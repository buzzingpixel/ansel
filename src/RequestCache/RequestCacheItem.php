<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\RequestCache;

use Exception;
use Psr\Cache\CacheItemInterface;

class RequestCacheItem implements CacheItemInterface
{
    private string $key;

    /** @var mixed */
    private $data;

    /**
     * @param mixed $data
     */
    public function __construct(
        string $key,
        $data = null
    ) {
        $this->key  = $key;
        $this->data = $data;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @inheritDoc
     */
    public function get()
    {
        return $this->data;
    }

    public function isHit(): bool
    {
        return $this->data !== null;
    }

    /**
     * @inheritDoc
     * @phpstan-ignore-next-line
     */
    public function set($value): self
    {
        $this->data = $value;

        return $this;
    }

    /**
     * @throws Exception
     *
     * @inheritDoc
     *
     * @phpstan-ignore-next-line
     */
    public function expiresAt($expiration): void
    {
        throw new Exception('Not implemented');
    }

    /**
     * @throws Exception
     *
     * @inheritDoc
     *
     * @phpstan-ignore-next-line
     */
    public function expiresAfter($time): void
    {
        throw new Exception('Not implemented');
    }
}
