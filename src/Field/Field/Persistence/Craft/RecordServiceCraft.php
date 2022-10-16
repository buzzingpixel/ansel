<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field\Persistence\Craft;

use BuzzingPixel\Ansel\Field\Field\Persistence\Fetching\FetchParameters;
use BuzzingPixel\Ansel\Field\Field\Persistence\Record;
use BuzzingPixel\Ansel\Field\Field\Persistence\RecordCollection;
use BuzzingPixel\Ansel\Field\Field\Persistence\RecordService;
use BuzzingPixel\Ansel\Field\Field\Persistence\SaveResult;

use function dd;

class RecordServiceCraft implements RecordService
{
    public function saveRecord(Record $record): SaveResult
    {
        // TODO: Implement saveRecord() method.
        dd('TODO: Implement saveRecord() method.');
    }

    public function saveRecords(RecordCollection $records): SaveResult
    {
        // TODO: Implement saveRecords() method.
        dd('TODO: Implement saveRecords() method.');
    }

    public function fetchRecord(
        FetchParameters $parameters,
        string $recordClass
    ): Record {
        // TODO: Implement fetchRecord() method.
        dd('TODO: Implement fetchRecord() method.');
    }

    public function fetchRecordOrNull(
        FetchParameters $parameters,
        string $recordClass
    ): ?Record {
        // TODO: Implement fetchRecord() method.
        dd('TODO: Implement fetchRecordOrNull() method.');
    }

    public function fetchRecords(
        FetchParameters $parameters,
        string $recordClass
    ): RecordCollection {
        // TODO: Implement fetchRecords() method.
        dd('TODO: Implement fetchRecords() method.');
    }
}
