<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Migrate\Migrations;

use BuzzingPixel\Ansel\Migrate\MigrationContract;
use ExpressionEngine\Model\Addon\Action;
use ExpressionEngine\Service\Model\Facade as RecordService;

use function assert;

class E0003AddLicensePingActionRecord implements MigrationContract
{
    private RecordService $recordService;

    public function __construct(RecordService $recordService)
    {
        $this->recordService = $recordService;
    }

    public function for(): string
    {
        return self::EE;
    }

    public function up(): bool
    {
        $record = $this->recordService->get('Action')
            ->filter('class', 'Ansel')
            ->filter('method', 'licensePing')
            ->first();

        if ($record !== null) {
            return true;
        }

        $record = $this->recordService->make('Action');

        assert($record instanceof Action);

        $record->setProperty('class', 'Ansel')
            ->setProperty('method', 'licensePing')
            ->setProperty('csrf_exempt', true)
            ->save();

        return true;
    }

    public function down(): bool
    {
        $record = $this->recordService->get('Action')
            ->filter('class', 'Ansel')
            ->filter('method', 'licensePing')
            ->first();

        if ($record === null) {
            return true;
        }

        assert($record instanceof Action);

        $record->delete();

        return true;
    }
}
