<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field\Persistence;

interface SaveRecords
{
    public function save(RecordCollection $records): SaveResult;
}
