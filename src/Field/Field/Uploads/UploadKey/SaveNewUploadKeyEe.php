<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field\Uploads;

use BuzzingPixel\Ansel\Field\Field\Uploads\UploadKey\SaveNewUploadKeyContract;
use BuzzingPixel\Ansel\Shared\ClockContract;
use BuzzingPixel\Ansel\Shared\EeQueryBuilderFactory;

class SaveNewUploadKeyEe implements SaveNewUploadKeyContract
{
    private ClockContract $clock;

    private EeQueryBuilderFactory $queryBuilderFactory;

    public function __construct(
        ClockContract $clock,
        EeQueryBuilderFactory $queryBuilderFactory
    ) {
        $this->queryBuilderFactory = $queryBuilderFactory;
        $this->clock               = $clock;
    }

    public function save(UploadKeyRecord $record): void
    {
        $this->queryBuilderFactory->create()->insert(
            'ansel_upload_keys',
            [
                'key' => $record->key,
                'created' => $this->clock->now()->getTimestamp(),
                'expires' => $record->expires,
            ],
        );
    }
}
