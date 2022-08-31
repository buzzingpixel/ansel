<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field\PostedFieldData;

use function array_map;
use function array_values;

class PostedFieldDataCollection
{
    /** @var PostedFieldData[] */
    private array $postedFieldData;

    /**
     * @param mixed[] $arrayData
     */
    public static function fromArray(array $arrayData): self
    {
        return new self(array_values(array_map(
            /** @phpstan-ignore-next-line */
            static fn (array $data) => PostedFieldData::fromArray(
                $data
            ),
            $arrayData,
        )));
    }

    /**
     * @param PostedFieldData[] $postedFieldData
     */
    public function __construct(array $postedFieldData)
    {
        $this->postedFieldData = array_values(array_map(
            static fn (PostedFieldData $data) => $data,
            $postedFieldData,
        ));
    }

    /**
     * @return PostedFieldData[]
     */
    public function postedFieldData(): array
    {
        return $this->postedFieldData;
    }
}
