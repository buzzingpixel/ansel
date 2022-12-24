<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\SourceHandling\Ee\Ee;

use BuzzingPixel\Ansel\SourceHandling\FileInstance;
use BuzzingPixel\Ansel\SourceHandling\FileInstanceCollection;
use ExpressionEngine\Model\File\File;
use ExpressionEngine\Service\Model\Facade as RecordService;

class EeGetFilesByIdentifiers
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

    /**
     * @param string[] $identifiers
     */
    public function get(array $identifiers): FileInstanceCollection
    {
        $records = $this->recordService->get('File')
            ->filter('file_id', 'IN', $identifiers)
            ->all();

        return new FileInstanceCollection(
            $records->map(
                function (File $record): FileInstance {
                    return $this->createFileInstance->create($record);
                },
            ),
        );
    }
}
