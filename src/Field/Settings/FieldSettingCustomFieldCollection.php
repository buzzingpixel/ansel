<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Settings;

use function array_map;
use function array_values;

class FieldSettingCustomFieldCollection
{
    /** @var FieldSettingCustomField[] */
    private array $fields;

    /**
     * @param FieldSettingCustomField[] $fields
     */
    public function __construct(array $fields = [])
    {
        $this->fields = array_values(array_map(
            static function (FieldSettingCustomField $field) {
                return $field;
            },
            $fields,
        ));
    }

    /**
     * @return FieldSettingCustomField[]
     */
    public function asArray(): array
    {
        return $this->fields;
    }

    public function addField(FieldSettingCustomField $field): void
    {
        $this->fields[] = $field;
    }
}
