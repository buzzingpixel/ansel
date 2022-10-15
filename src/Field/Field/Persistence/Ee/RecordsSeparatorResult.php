<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field\Persistence\Ee;

use BuzzingPixel\Ansel\Field\Field\Persistence\RecordCollection;

class RecordsSeparatorResult
{
    private RecordCollection $new;

    private RecordCollection $existing;

    public function __construct(
        RecordCollection $new,
        RecordCollection $existing
    ) {
        $this->new      = $new;
        $this->existing = $existing;
    }

    public function new(): RecordCollection
    {
        return $this->new;
    }

    public function existing(): RecordCollection
    {
        return $this->existing;
    }
}
