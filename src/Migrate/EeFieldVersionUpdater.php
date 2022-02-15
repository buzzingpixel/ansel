<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Migrate;

use BuzzingPixel\Ansel\Shared\Meta\Meta;
use ExpressionEngine\Model\Addon\Fieldtype;
use ExpressionEngine\Service\Model\Facade as RecordService;

use function assert;

class EeFieldVersionUpdater
{
    private Meta $meta;

    private RecordService $recordService;

    public function __construct(
        Meta $meta,
        RecordService $recordService
    ) {
        $this->meta          = $meta;
        $this->recordService = $recordService;
    }

    public function update(): void
    {
        $record = $this->recordService->get('Fieldtype')
            ->filter('name', 'ansel')
            ->first();

        if ($record === null) {
            return;
        }

        assert($record instanceof Fieldtype);

        $record->setProperty('version', $this->meta->version())
            ->save();
    }
}
