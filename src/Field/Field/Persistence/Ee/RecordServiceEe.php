<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field\Persistence\Ee;

use BuzzingPixel\Ansel\Field\Field\Persistence\Fetching\FetchParameters;
use BuzzingPixel\Ansel\Field\Field\Persistence\Record;
use BuzzingPixel\Ansel\Field\Field\Persistence\RecordCollection;
use BuzzingPixel\Ansel\Field\Field\Persistence\RecordService;
use BuzzingPixel\Ansel\Field\Field\Persistence\SaveResult;
use ReflectionException;

class RecordServiceEe implements RecordService
{
    private SaveRecords $saveRecords;

    private FetchRecords $fetchRecords;

    public function __construct(
        SaveRecords $saveRecords,
        FetchRecords $fetchRecords
    ) {
        $this->saveRecords  = $saveRecords;
        $this->fetchRecords = $fetchRecords;
    }

    public function saveRecord(Record $record): SaveResult
    {
        return $this->saveRecords->save(
            new RecordCollection([$record]),
        );
    }

    public function saveRecords(RecordCollection $records): SaveResult
    {
        return $this->saveRecords->save($records);
    }

    /**
     * @throws ReflectionException
     */
    public function fetchRecord(
        FetchParameters $parameters,
        string $recordClass
    ): Record {
        $parameters = $parameters->withLimit(1);

        return $this->fetchRecords->fetch(
            $parameters,
            $recordClass,
        )->first();
    }

    /**
     * @throws ReflectionException
     */
    public function fetchRecordOrNull(
        FetchParameters $parameters,
        string $recordClass
    ): ?Record {
        $parameters = $parameters->withLimit(1);

        return $this->fetchRecords->fetch(
            $parameters,
            $recordClass,
        )->firstOrNull();
    }

    /**
     * @throws ReflectionException
     */
    public function fetchRecords(
        FetchParameters $parameters,
        string $recordClass
    ): RecordCollection {
        return $this->fetchRecords->fetch(
            $parameters,
            $recordClass,
        );
    }
}
