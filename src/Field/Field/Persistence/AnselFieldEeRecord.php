<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field\Persistence;

// phpcs:disable Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps

class AnselFieldEeRecord extends Record
{
    public static function tableName(): string
    {
        return 'ansel_field_data';
    }

    public string $ansel_image_ansel_id;

    public string $handle;

    public string $value;
}
