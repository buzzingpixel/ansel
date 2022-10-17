<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field\PostedFieldData;

use function array_map;
use function array_values;

class PostedDeletionsCollection
{
    /**
     * @param mixed[] $arrayData
     */
    public static function fromArray(array $arrayData): self
    {
        return new self(array_values(array_map(
            /** @phpstan-ignore-next-line */
            static fn (array $data) => PostedDeletion::fromArray(
                $data,
            ),
            $arrayData,
        )));
    }

    /** @var PostedDeletion[] */
    private array $postedDeletions;

    /**
     * @param PostedDeletion[] $postedDeletions
     */
    public function __construct(array $postedDeletions = [])
    {
        $this->postedDeletions = array_values(array_map(
            static function (PostedDeletion $postedDeletion) {
                return $postedDeletion;
            },
            $postedDeletions,
        ));
    }

    /**
     * @return PostedDeletion[]
     */
    public function postedDeletions(): array
    {
        return $this->postedDeletions;
    }

    /**
     * @param callable(PostedDeletion $image): ReturnType $callback
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
     * @return PostedDeletion[]
     */
    public function asArray(): array
    {
        return $this->postedDeletions;
    }

    /**
     * @return mixed[]
     */
    public function asScalarArray(): array
    {
        return $this->map(static function (
            PostedDeletion $deletion
        ): string {
            return $deletion->uid();
        });
    }
}
