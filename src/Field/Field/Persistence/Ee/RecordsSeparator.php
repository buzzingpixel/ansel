<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field\Persistence\Ee;

use BuzzingPixel\Ansel\Field\Field\Persistence\Record;
use BuzzingPixel\Ansel\Field\Field\Persistence\RecordCollection;

class RecordsSeparator
{
    public function separate(RecordCollection $records): RecordsSeparatorResult
    {
        return new RecordsSeparatorResult(
            $records->filter(
                static fn (Record $r) => $r->isNew(),
            ),
            $records->filter(
                static fn (Record $r) => ! $r->isNew(),
            )
        );
    }
}
