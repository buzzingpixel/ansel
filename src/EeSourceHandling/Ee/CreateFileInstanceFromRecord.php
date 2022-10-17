<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\EeSourceHandling\Ee;

use BuzzingPixel\Ansel\EeSourceHandling\FileInstance;
use ExpressionEngine\Model\File\File;
use ExpressionEngine\Model\File\UploadDestination;

use function assert;
use function is_bool;
use function is_float;
use function is_int;
use function is_string;

class CreateFileInstanceFromRecord
{
    public function create(File $record): FileInstance
    {
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
            (string) $record->getId(),
            (string) $uploadDestination->getId(),
            $record->getAbsolutePath(),
            $record->getAbsoluteURL(),
            (int) $fileSize,
            (int) $record->get__width(),
            (int) $record->get__height(),
        );
    }
}
