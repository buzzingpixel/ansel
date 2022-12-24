<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\SourceHandling\Ee\Ee;

use BuzzingPixel\Ansel\SourceHandling\FileInstance;
use ExpressionEngine\Model\File\File;
use ExpressionEngine\Service\Model\Facade as RecordService;

use function assert;

class EeGetFileByIdentifier
{
    private RecordService $recordService;

    private EeCreateFileInstanceFromRecord $createFileInstance;

    public function __construct(
        RecordService $recordService,
        EeCreateFileInstanceFromRecord $createFileInstance
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
