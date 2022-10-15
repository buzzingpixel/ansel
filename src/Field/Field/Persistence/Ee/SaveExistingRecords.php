<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field\Persistence\Ee;

use BuzzingPixel\Ansel\Field\Field\Persistence\RecordCollection;
use BuzzingPixel\Ansel\Field\Field\Persistence\SaveRecords;
use BuzzingPixel\Ansel\Field\Field\Persistence\SaveResult;
use BuzzingPixel\Ansel\Field\Field\Persistence\SaveResultInstance;
use BuzzingPixel\Ansel\Shared\EeQueryBuilderFactory;

class SaveExistingRecords implements SaveRecords
{
    private EeQueryBuilderFactory $queryBuilderFactory;

    public function __construct(EeQueryBuilderFactory $queryBuilderFactory)
    {
        $this->queryBuilderFactory = $queryBuilderFactory;
    }

    public function save(RecordCollection $records): SaveResult
    {
        if ($records->count() < 1) {
            return new SaveResultInstance(true);
        }

        $wasSuccessful = $this->queryBuilderFactory->create()->update_batch(
            $records->tableName(),
            $records->asScalarArray(),
            'id',
        );

        return new SaveResultInstance($wasSuccessful);
    }
}
