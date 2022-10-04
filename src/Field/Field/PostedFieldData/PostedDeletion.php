<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field\PostedFieldData;

class PostedDeletion
{
    private string $id;

    /**
     * @param mixed[] $arrayData
     */
    public static function fromArray(array $arrayData): self
    {
        return new self(
            /** @phpstan-ignore-next-line */
            (string) ($arrayData['id'] ?? ''),
        );
    }

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function uid(): string
    {
        return $this->id;
    }

    /**
     * @return mixed[]
     */
    public function asScalarArray(): array
    {
        return [
            'uuid' => $this->id,
        ];
    }
}
