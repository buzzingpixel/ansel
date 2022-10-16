<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field\Persistence;

use BuzzingPixel\Ansel\Field\Field\Persistence\Fetching\FetchParameters;

interface RecordService
{
    public function saveRecord(Record $record): SaveResult;

    /**
     * @param RecordCollection<Record> $records
     */
    public function saveRecords(RecordCollection $records): SaveResult;

    /**
     * @param class-string<R> $recordClass
     *
     * @return R
     *
     * @template R of Record
     */
    public function fetchRecord(
        FetchParameters $parameters,
        string $recordClass
    ): Record;

    /**
     * @param class-string<R> $recordClass
     *
     * @return R|null
     *
     * @template R of Record
     */
    public function fetchRecordOrNull(
        FetchParameters $parameters,
        string $recordClass
    ): ?Record;

    /**
     * @param class-string<R> $recordClass
     *
     * @return RecordCollection<R>
     *
     * @template R of Record
     */
    public function fetchRecords(
        FetchParameters $parameters,
        string $recordClass
    ): RecordCollection;
}
