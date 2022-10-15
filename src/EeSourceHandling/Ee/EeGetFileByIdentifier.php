<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\EeSourceHandling\Ee;

use BuzzingPixel\Ansel\EeSourceHandling\FileInstance;
use ExpressionEngine\Model\File\File;
use ExpressionEngine\Model\File\UploadDestination;
use ExpressionEngine\Service\Model\Facade as RecordService;

use function assert;
use function is_bool;
use function is_float;
use function is_int;
use function is_string;

class EeGetFileByIdentifier
{
    private RecordService $recordService;

    public function __construct(RecordService $recordService)
    {
        $this->recordService = $recordService;
    }

    public function get(string $identifier): ?FileInstance
    {
        $record = $this->recordService->get('File')
            ->filter('file_id', $identifier)
            ->first();

        assert($record instanceof File || $record === null);

        if ($record === null) {
            return null;
        }

        /**
         * @phpstan-ignore-next-line
         * phpcs:disable Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps
         */
        $uploadDestination = $record->UploadDestination;

        assert($uploadDestination instanceof UploadDestination);

        $fileSize = $record->getProperty('file_size');

        assert(
            is_int($fileSize) ||
            is_float($fileSize) ||
            is_bool($fileSize) ||
            is_string($fileSize)
        );

        return new FileInstance(
            EeSourceAdapter::class,
            $uploadDestination->getPrimaryKey(),
            $record->getPrimaryKey(),
            $record->getAbsolutePath(),
            $record->getAbsoluteURL(),
            (int) $fileSize,
            (int) $record->get__width(),
            (int) $record->get__height(),
        );
    }
}
