<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field;

class FieldRenderModel
{
    /** @var scalar[] */
    private array $fieldSettings;

    /** @var scalar[] */
    private array $customFields;

    /**
     * @param scalar[] $fieldSettings
     * @param scalar[] $customFields
     */
    public function __construct(
        array $fieldSettings,
        array $customFields
    ) {
        $this->fieldSettings = $fieldSettings;
        $this->customFields  = $customFields;
    }

    /**
     * @return scalar[]
     */
    public function fieldSettings(): array
    {
        return $this->fieldSettings;
    }

    /**
     * @return scalar[]
     */
    public function customFields(): array
    {
        return $this->customFields;
    }
}
