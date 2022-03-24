<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field;

class FieldRenderModel
{
    /** @var scalar[] */
    private array $fieldSettings;

    /** @var scalar[] */
    private array $customFields;

    /** @var scalar[] */
    private array $parameters;

    /**
     * @param scalar[] $fieldSettings
     * @param scalar[] $customFields
     * @param scalar[] $parameters
     */
    public function __construct(
        array $fieldSettings,
        array $customFields,
        array $parameters
    ) {
        $this->fieldSettings = $fieldSettings;
        $this->customFields  = $customFields;
        $this->parameters    = $parameters;
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

    /**
     * @return scalar[]
     */
    public function parameters(): array
    {
        return $this->parameters;
    }
}
