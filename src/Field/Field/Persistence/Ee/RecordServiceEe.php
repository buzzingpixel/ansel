<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field\Persistence\Ee;

use BuzzingPixel\Ansel\Field\Field\Persistence\FetchParameters;
use BuzzingPixel\Ansel\Field\Field\Persistence\Record;
use BuzzingPixel\Ansel\Field\Field\Persistence\RecordCollection;
use BuzzingPixel\Ansel\Field\Field\Persistence\RecordService;
use BuzzingPixel\Ansel\Field\Field\Persistence\SaveResult;

use function dd;

class RecordServiceEe implements RecordService
{
    private SaveRecords $saveRecords;

    public function __construct(SaveRecords $saveRecords)
    {
        $this->saveRecords = $saveRecords;
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

    public function fetchRecord(FetchParameters $parameters): ?Record
    {
        // TODO: Implement fetchRecord() method.
        dd('TODO: Implement fetchRecord() method.');
    }

    public function fetchRecords(FetchParameters $parameters): RecordCollection
    {
        // TODO: Implement fetchRecords() method.
        dd('TODO: Implement fetchRecords() method.');
    }
}
