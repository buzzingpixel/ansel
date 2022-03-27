<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field\Uploads\UploadKey;

use BuzzingPixel\Ansel\Shared\ClockContract;
use BuzzingPixel\Ansel\Shared\EeQueryBuilderFactory;

class ValidateUploadKeyEe implements ValidateUploadKeyContract
{
    private ClockContract $clock;

    private EeQueryBuilderFactory $queryBuilderFactory;

    public function __construct(
        ClockContract $clock,
        EeQueryBuilderFactory $queryBuilderFactory
    ) {
        $this->clock               = $clock;
        $this->queryBuilderFactory = $queryBuilderFactory;
    }

    public function validate(string $key): bool
    {
        $this->garbageCollectExpiredKeys();

        return $this->validateKey($key);
    }

    private function garbageCollectExpiredKeys(): void
    {
        $this->queryBuilderFactory->create()
            ->where(
                'expires <',
                $this->clock->now()->getTimestamp(),
            )
            ->delete('ansel_upload_keys');
    }

    private function validateKey(string $key): bool
    {
        $count = $this->queryBuilderFactory->create()
            ->where('key', $key)
            ->count_all_results('ansel_upload_keys');

        return $count > 0;
    }
}
