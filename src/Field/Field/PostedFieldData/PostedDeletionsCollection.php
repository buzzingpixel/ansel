<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field\PostedFieldData;

use function array_map;
use function array_values;

class PostedDeletionsCollection
{
    /**
     * @param mixed[] $postedImages
     */
    public static function fromArray(array $postedImages): self
    {
        return new self(array_values(array_map(
            /** @phpstan-ignore-next-line */
            static function (array $postedDeletion) {
                return PostedDeletion::fromArray(
                    $postedDeletion,
                );
            },
            $postedImages,
        )));
    }

    /** @var PostedDeletion[] */
    private array $postedDeletions;

    /**
     * @param PostedDeletion[] $postedDeletions
     */
    public function __construct(array $postedDeletions)
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
}
