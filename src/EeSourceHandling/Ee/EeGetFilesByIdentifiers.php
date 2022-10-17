<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\EeSourceHandling\Ee;

use BuzzingPixel\Ansel\EeSourceHandling\FileInstance;
use BuzzingPixel\Ansel\EeSourceHandling\FileInstanceCollection;
use ExpressionEngine\Model\File\File;
use ExpressionEngine\Service\Model\Facade as RecordService;

class EeGetFilesByIdentifiers
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
