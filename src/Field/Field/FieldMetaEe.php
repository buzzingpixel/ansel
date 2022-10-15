<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field;

class FieldMetaEe
{
    private int $fieldId;

    private string $fieldName;

    private EeContentType $contentType;

    private int $sourceId;

    private int $contentId;

    private ?int $rowId;

    private ?int $colId;

    private ?int $varId;

    private ?int $fluidFieldId;

    public function __construct(
        int $fieldId,
        string $fieldName,
        EeContentType $contentType,
        int $sourceId,
        int $contentId,
        ?int $rowId = null,
        ?int $colId = null,
        ?int $varId = null,
        ?int $fluidFieldId = null
    ) {
        $this->fieldId      = $fieldId;
        $this->fieldName    = $fieldName;
        $this->contentType  = $contentType;
        $this->sourceId     = $sourceId;
        $this->contentId    = $contentId;
        $this->rowId        = $rowId;
        $this->colId        = $colId;
        $this->varId        = $varId;
        $this->fluidFieldId = $fluidFieldId;
    }

    public function fieldId(): int
    {
        return $this->fieldId;
    }

    public function fieldName(): string
    {
        return $this->fieldName;
    }

    public function contentType(): EeContentType
    {
        return $this->contentType;
    }

    public function sourceId(): int
    {
        return $this->sourceId;
    }

    public function contentId(): int
    {
        return $this->contentId;
    }

    public function rowId(): ?int
    {
        return $this->rowId;
    }

    public function colId(): ?int
    {
        return $this->colId;
    }

    public function varId(): ?int
    {
        return $this->varId;
    }

    public function fluidFieldId(): ?int
    {
        return $this->fluidFieldId;
    }
}
