<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Migrate;

use BuzzingPixel\Ansel\Shared\Meta;
use ExpressionEngine\Model\Addon\Module;
use ExpressionEngine\Service\Model\Facade as RecordService;

use function assert;

class EeModuleVersionUpdater
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
        $record = $this->recordService->get('Module')
            ->filter('module_name', 'Ansel')
            ->first();

        if ($record === null) {
            return;
        }

        assert($record instanceof Module);

        $record->setProperty('module_name', 'Ansel')
            ->setProperty(
                'module_version',
                $this->meta->version()
            )
            ->setProperty('has_cp_backend', 'y')
            ->setProperty('has_publish_fields', 'n')
            ->save();
    }
}
