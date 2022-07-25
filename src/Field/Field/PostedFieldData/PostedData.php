<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field\PostedFieldData;

use function is_array;

class PostedData
{
    private PostedImageCollection $postedImages;

    private PostedDeletionsCollection $postedDeletions;

    /**
     * @param mixed[] $postedFieldData
     */
    public static function fromArray(array $postedFieldData): self
    {
        /** @phpstan-ignore-next-line */
        $postedImagesArrayData = $postedFieldData['field']['images'] ?? [];

        $postedImagesArrayData = is_array($postedImagesArrayData) ?
            $postedImagesArrayData :
            [];

        /** @phpstan-ignore-next-line */
        $postedDeletionsArray = $postedFieldData['field']['delete'] ?? [];

        $postedDeletionsArray = is_array($postedDeletionsArray) ?
            $postedDeletionsArray :
            [];

        $postedDeletionsMapped = [];

        foreach ($postedDeletionsArray as $uid) {
            $postedDeletionsMapped[]['uid'] = $uid;
        }

        return new self(
            PostedImageCollection::fromArray(
                $postedImagesArrayData,
            ),
            PostedDeletionsCollection::fromArray(
                $postedDeletionsMapped,
            ),
        );
    }

    public function __construct(
        PostedImageCollection $postedImages,
        PostedDeletionsCollection $postedDeletions
    ) {
        $this->postedImages    = $postedImages;
        $this->postedDeletions = $postedDeletions;
    }

    public function postedImages(): PostedImageCollection
    {
        return $this->postedImages;
    }

    public function postedDeletions(): PostedDeletionsCollection
    {
        return $this->postedDeletions;
    }
}
