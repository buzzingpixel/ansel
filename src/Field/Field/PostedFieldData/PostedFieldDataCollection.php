<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field\PostedFieldData;

use function array_filter;
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
    public function __construct(array $postedFieldData = [])
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

    public function findByHandle(string $handle): ?PostedFieldData
    {
        return array_values(array_filter(
            $this->postedFieldData,
            static function (PostedFieldData $fieldData) use (
                $handle
            ): bool {
                return $fieldData->handle() === $handle;
            }
        ))[0] ?? null;
    }

    /**
     * @param callable(PostedFieldData $fieldData): ReturnType $callback
     *
     * @return ReturnType[]
     *
     * @template ReturnType
     */
    public function map(callable $callback): array
    {
        return array_values(array_map(
            $callback,
            $this->asArray()
        ));
    }

    /**
     * @return PostedFieldData[]
     */
    public function asArray(): array
    {
        return $this->postedFieldData;
    }

    /**
     * @return mixed[]
     */
    public function asScalarArray(): array
    {
        return $this->map(static function (
            PostedFieldData $fieldData
        ): array {
            return $fieldData->asScalarArray();
        });
    }
}
