<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Migrate\Migrations;

use BuzzingPixel\Ansel\Migrate\MigrationContract;
use BuzzingPixel\Ansel\Shared\Meta\Meta;
use ExpressionEngine\Model\Addon\Module;
use ExpressionEngine\Service\Model\Facade as RecordService;

use function assert;

class E0002AddModuleRecord implements MigrationContract
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

    public function for(): string
    {
        return self::EE;
    }

    public function up(): bool
    {
        $record = $this->recordService->get('Module')
            ->filter('module_name', 'Ansel')
            ->first();

        if ($record !== null) {
            return true;
        }

        $record = $this->recordService->make('Module');

        assert($record instanceof Module);

        $record->setProperty('module_name', 'Ansel')
            ->setProperty(
                'module_version',
                $this->meta->version()
            )
            ->setProperty('has_cp_backend', 'y')
            ->setProperty('has_publish_fields', 'n')
            ->save();

        return true;
    }

    public function down(): bool
    {
        $record = $this->recordService->get('Module')
            ->filter('module_name', 'Ansel')
            ->first();

        if ($record === null) {
            return true;
        }

        assert($record instanceof Module);

        $record->delete();

        return true;
    }
}
