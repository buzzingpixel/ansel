<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\SourceHandling\Ee\Ee;

use BuzzingPixel\Ansel\Shared\Php\InternalFunctions;
use Exception;
use ExpressionEngine\Model\File\UploadDestination;
use ExpressionEngine\Service\Model\Facade as RecordService;
use SplFileInfo;

use function assert;
use function ltrim;
use function rtrim;

use const DIRECTORY_SEPARATOR;

class EeUploadFile
{
    private RecordService $recordService;

    private InternalFunctions $functions;

    public function __construct(
        RecordService $recordService,
        InternalFunctions $functions
    ) {
        $this->recordService = $recordService;
        $this->functions     = $functions;
    }

    /**
     * @throws Exception
     */
    public function upload(
        string $locationIdentifier,
        SplFileInfo $file,
        ?string $subFolder = null
    ): SplFileInfo {
        $sep = DIRECTORY_SEPARATOR;

        $location = $this->recordService->get('UploadDestination')
            ->filter('id', $locationIdentifier)
            ->first();

        assert(
            $location instanceof UploadDestination ||
            $location === null
        );

        if ($location === null) {
            throw new Exception('Location not found');
        }

        /** @phpstan-ignore-next-line */
        $uploadPath = (string) $location->getProperty('server_path');

        $uploadPath = rtrim($uploadPath, $sep) . $sep;

        if ($subFolder !== null) {
            $uploadPath .= rtrim(ltrim(
                $subFolder,
                $sep
            ), $sep) . $sep;
        }

        $fullUploadPath = $uploadPath . $file->getBasename();

        if ($this->functions->fileExists($fullUploadPath)) {
            $ext = '.' . $file->getExtension();

            $fullUploadPath = $uploadPath .
                $file->getBasename($ext) .
                '-' . $this->functions->uniqid() .
                $ext;
        }

        // Make sure PHP can write file permissions
        $oldMask = $this->functions->umask(0);

        if (! $this->functions->isDir($uploadPath)) {
            $this->functions->mkdir($uploadPath);
        }

        $this->functions->copy(
            $file->getPathname(),
            $fullUploadPath,
        );

        $this->functions->umask($oldMask);

        return new SplFileInfo($fullUploadPath);
    }
}
