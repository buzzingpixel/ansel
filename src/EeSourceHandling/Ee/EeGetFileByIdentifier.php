<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\EeSourceHandling\Ee;

use BuzzingPixel\Ansel\EeSourceHandling\FileInstance;
use ExpressionEngine\Model\File\File;
use ExpressionEngine\Service\Model\Facade as RecordService;

use function assert;

class EeGetFileByIdentifier
{
    private RecordService $recordService;

    private CreateFileInstanceFromRecord $createFileInstance;

    public function __construct(
        RecordService $recordService,
        CreateFileInstanceFromRecord $createFileInstance
    ) {
        $this->recordService      = $recordService;
        $this->createFileInstance = $createFileInstance;
    }

    public function get(string $identifier): ?FileInstance
    {
        $record = $this->recordService->get('File')
            ->filter('file_id', $identifier)
            ->first();

        assert($record instanceof File || $record === null);

        if ($record === null) {
            return null;
        }

        return $this->createFileInstance->create($record);
    }
}