<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field\Persistence\Ee;

use BuzzingPixel\Ansel\Field\Field\Persistence\RecordCollection;
use BuzzingPixel\Ansel\Field\Field\Persistence\SaveResult;
use BuzzingPixel\Ansel\Field\Field\Persistence\SaveResultInstance;

class SaveRecords
{
    private SaveNewRecords $saveNewRecords;

    private RecordsSeparator $recordsSeparator;

    private SaveExistingRecords $saveExistingRecords;

    public function __construct(
        SaveNewRecords $saveNewRecords,
        RecordsSeparator $recordsSeparator,
        SaveExistingRecords $saveExistingRecords
    ) {
        $this->saveNewRecords      = $saveNewRecords;
        $this->recordsSeparator    = $recordsSeparator;
        $this->saveExistingRecords = $saveExistingRecords;
    }

    public function save(RecordCollection $records): SaveResult
    {
        $separated = $this->recordsSeparator->separate($records);

        $new = $this->saveNewRecords->save(
            $separated->new(),
        );

        $existing = $this->saveExistingRecords->save(
            $separated->existing(),
        );

        return new SaveResultInstance(
            $new->wasSuccessful() && $existing->wasSuccessful(),
        );
    }
}
