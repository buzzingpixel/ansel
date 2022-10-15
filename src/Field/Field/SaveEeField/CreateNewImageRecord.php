<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field\SaveEeField;

use BuzzingPixel\Ansel\EeSourceHandling\File;
use BuzzingPixel\Ansel\Field\Field\Persistence\AnselImageEeRecord;
use BuzzingPixel\Ansel\Field\Field\PostedFieldData\PostedImage;
use BuzzingPixel\Ansel\Shared\EE\SiteMeta;
use BuzzingPixel\Ansel\Shared\SystemClock;

// phpcs:disable Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps

class CreateNewImageRecord
{
    private SystemClock $clock;

    public function __construct(SystemClock $clock)
    {
        $this->clock = $clock;
    }

    public function create(
        PostedImage $image,
        Payload $payload,
        int $index,
        File $sourceFile,
        File $savedFile,
        SiteMeta $siteMeta,
        ?int $memberId = null
    ): AnselImageEeRecord {
        $meta = $payload->fieldMetaEe();

        $imageRecord = new AnselImageEeRecord();

        $imageRecord->ansel_id = $image->id();

        $imageRecord->site_id = $siteMeta->siteId();

        $imageRecord->source_id = $payload->fieldMetaEe()->sourceId();

        $imageRecord->content_id = $meta->contentId();

        $imageRecord->field_id = $meta->fieldId();

        $imageRecord->content_type = $meta->contentType()->getValue();

        $imageRecord->row_id = $meta->rowId();

        $imageRecord->col_id = $meta->colId();

        $imageRecord->file_id = (int) $savedFile->identifier();

        $imageRecord->original_location_type = $payload->fieldSettings()
            ->uploadLocation()
            ->directoryType();

        $imageRecord->original_file_id = (int) $sourceFile->identifier();

        $imageRecord->save_location_type = $payload->fieldSettings()
            ->saveLocation()
            ->directoryType();

        $imageRecord->save_location_id = $payload->fieldSettings()
            ->saveLocation()
            ->directoryId();

        $imageRecord->filename = $savedFile->fileName();

        $imageRecord->extension = $savedFile->fileNameExtension();

        $imageRecord->original_extension = $sourceFile->fileNameExtension();

        $imageRecord->filesize = $savedFile->filesize();

        $imageRecord->original_filesize = $sourceFile->filesize();

        $imageRecord->width = (int) $image->width();

        $imageRecord->height = (int) $image->height();

        $imageRecord->x = (int) $image->x();

        $imageRecord->y = (int) $image->y();

        $imageRecord->focal_x = (int) $image->focalX();

        $imageRecord->focal_y = (int) $image->focalY();

        $imageRecord->member_id = $memberId ?? 0;

        $imageRecord->position = $index + 1;

        $imageRecord->upload_date = $this->clock->now()->getTimestamp();

        $imageRecord->modify_date = $this->clock->now()->getTimestamp();

        $maxQty = $payload->fieldSettings()->maxQty()->value();

        $imageRecord->disabled = $maxQty <= $imageRecord->position ? 0 : 1;

        return $imageRecord;
    }
}
