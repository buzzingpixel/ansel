<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field\Uploads;

use BuzzingPixel\Ansel\Shared\ClockContract;
use DateInterval;

use function bin2hex;
use function random_bytes;

class GetUploadKey implements GetUploadKeyContract
{
    private ClockContract $clock;

    private SaveNewUploadKeyContract $saveNewUploadKey;

    public function __construct(
        ClockContract $clock,
        SaveNewUploadKeyContract $saveNewUploadKey
    ) {
        $this->clock            = $clock;
        $this->saveNewUploadKey = $saveNewUploadKey;
    }

    public function get(): string
    {
        // 32 character token
        /** @noinspection PhpUnhandledExceptionInspection */
        $token = bin2hex(random_bytes(16));

        $record = new UploadKeyRecord();

        $record->key = $token;

        $record->expires = $this->clock->now()
            ->add(new DateInterval('PT2H'))
            ->getTimestamp();

        $this->saveNewUploadKey->save($record);

        return $token;
    }
}
