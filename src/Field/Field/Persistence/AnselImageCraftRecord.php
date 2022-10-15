<?php

declare(strict_types=1);

// phpcs:disable Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps

namespace BuzzingPixel\Ansel\Field\Field\Persistence;

class AnselImageCraftRecord extends Record
{
    public static function tableName(): string
    {
        return 'ansel_images';
    }

    public string $ansel_id;

    public int $element_id;

    public int $field_id;

    public int $user_id;

    public int $asset_id;

    public int $high_qual_asset_id;

    public int $thumb_asset_id;

    public int $original_asset_id;

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

    public int $position;

    public int $disabled;

    public string $dateCreated;

    public string $dateUpdated;

    public string $uid;
}
