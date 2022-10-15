<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field\Persistence;

interface RecordService
{
    public function saveRecord(Record $record): SaveResult;

    public function saveRecords(RecordCollection $records): SaveResult;

    public function fetchRecord(FetchParameters $parameters): ?Record;

    public function fetchRecords(FetchParameters $parameters): RecordCollection;
}
