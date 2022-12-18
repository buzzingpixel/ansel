<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\SourceHandling\Ee\Ee;

use BuzzingPixel\Ansel\Shared\ClockContract;
use BuzzingPixel\Ansel\Shared\MimeTypeDetector;
use BuzzingPixel\Ansel\Shared\Php\InternalFunctions;
use BuzzingPixel\Ansel\Shared\SiteMeta;
use BuzzingPixel\Ansel\SourceHandling\FileInstance;
use CI_DB_result;
use Exception;
use ExpressionEngine\Model\File\File as FileRecord;
use ExpressionEngine\Model\File\UploadDestination;
use ExpressionEngine\Service\Model\Facade as RecordService;
use File_model;
use Filemanager;
use SplFileInfo;

use function assert;
use function is_bool;
use function is_float;
use function is_int;
use function is_string;

class EeAddFile
{
    private SiteMeta $siteMeta;

    private File_model $eeFileModel;

    private EeUploadFile $uploadFile;

    private ClockContract $systemClock;

    private Filemanager $eeFileManager;

    private InternalFunctions $functions;

    private RecordService $recordService;

    private MimeTypeDetector $mimeTypeDetector;

    public function __construct(
        SiteMeta $siteMeta,
        File_model $eeFileModel,
        EeUploadFile $uploadFile,
        ClockContract $systemClock,
        Filemanager $eeFileManager,
        InternalFunctions $functions,
        RecordService $recordService,
        MimeTypeDetector $mimeTypeDetector
    ) {
        $this->siteMeta         = $siteMeta;
        $this->eeFileModel      = $eeFileModel;
        $this->uploadFile       = $uploadFile;
        $this->systemClock      = $systemClock;
        $this->eeFileManager    = $eeFileManager;
        $this->functions        = $functions;
        $this->recordService    = $recordService;
        $this->mimeTypeDetector = $mimeTypeDetector;
    }

    /**
     * @throws Exception
     */
    public function add(
        string $locationIdentifier,
        SplFileInfo $file,
        string $memberId = ''
    ): FileInstance {
        $uploadedFile = $this->uploadFile->upload(
            $locationIdentifier,
            $file,
        );

        $imageSize = $this->functions->getImageSize(
            $uploadedFile->getPathname()
        );

        $record = $this->recordService->make('File');

        assert($record instanceof FileRecord);

        $record->setProperty('site_id', $this->siteMeta->siteId());

        $record->setProperty(
            'title',
            $uploadedFile->getBasename(),
        );

        $record->setProperty(
            'upload_location_id',
            $locationIdentifier,
        );

        $record->setProperty(
            'mime_type',
            $this->mimeTypeDetector->detect(
                $uploadedFile->getPathname(),
            ),
        );

        $record->setProperty(
            'file_name',
            $uploadedFile->getBasename(),
        );

        $record->setProperty(
            'file_size',
            $uploadedFile->getSize(),
        );

        $record->setProperty('uploaded_by_member_id', $memberId);

        $record->setProperty(
            'upload_date',
            $this->systemClock->now()->getTimestamp(),
        );

        $record->setProperty('modified_by_member_id', $memberId);

        $record->setProperty(
            'modified_date',
            $this->systemClock->now()->getTimestamp(),
        );

        $record->setProperty(
            'file_hw_original',
            /** @phpstan-ignore-next-line */
            ((string) $imageSize[1]) . ' ' . ((string) $imageSize[0]),
        );

        $record->save();

        $dimensions = $this->eeFileModel->get_dimensions_by_dir_id(
            $locationIdentifier,
        );

        assert($dimensions instanceof CI_DB_result);

        $dimensions = $dimensions->result_array();

        /**
         * @phpstan-ignore-next-line
         * phpcs:disable Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps
         */
        $uploadDestination = $record->UploadDestination;

        assert($uploadDestination instanceof UploadDestination);

        // Run manipulations and thumbnails
        $this->eeFileManager->create_thumb(
            $uploadedFile->getPathname(),
            [
                'directory' => $uploadDestination,
                'server_path' => $uploadedFile->getPath(),
                'file_name' => $record->getProperty('file_name'),
                'dimensions' => $dimensions,
                'mime_type' => $record->getProperty('mime_type'),
            ],
            true,
            false
        );

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
