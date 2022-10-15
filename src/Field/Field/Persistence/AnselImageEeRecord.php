<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field\Persistence;

// phpcs:disable Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps

class AnselImageEeRecord extends Record
{
    public static function tableName(): string
    {
        return 'ansel_images';
    }

    public string $ansel_id;

    public int $site_id;

    public int $source_id;

    public int $content_id;

    public int $field_id;

    public string $content_type;

    public ?int $row_id;

    public ?int $col_id;

    public int $file_id;

    public string $original_location_type;

    public int $original_file_id;

    public string $save_location_type;

    public string $save_location_id;

    public string $filename;

    public string $extension;

    public string $original_extension;

    public int $filesize;

    public int $original_filesize;

    public int $width;

    public int $height;

    public int $x;

    public int $y;

    public int $focal_x;

    public int $focal_y;

    /** Deprecated — using fields now */
    public string $title;

    /** Deprecated — using fields now */
    public string $caption;

    /** Deprecated — using fields now */
    public int $cover;

    public int $member_id;

    public int $position;

    public int $upload_date;

    public int $modify_date;

    public int $disabled;
}
