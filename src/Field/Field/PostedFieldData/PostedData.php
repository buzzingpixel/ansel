<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field\PostedFieldData;

use function array_map;
use function array_values;
use function is_array;
use function json_decode;

class PostedData
{
    private PostedImageCollection $postedImages;

    private PostedDeletionsCollection $postedDeletions;

    /**
     * @param mixed[] $postedFieldData
     */
    public static function fromArray(array $postedFieldData): self
    {
        return new self(
            self::imageCollectionFromArray(
                $postedFieldData
            ),
            self::deletionsCollectionFromArray(
                $postedFieldData
            ),
        );
    }

    /**
     * @param mixed[] $postedFieldData
     */
    private static function imageCollectionFromArray(
        array $postedFieldData
    ): PostedImageCollection {
        $postedImagesArrayData = $postedFieldData['images'] ?? [];

        $postedImagesArrayData = is_array($postedImagesArrayData) ?
            $postedImagesArrayData :
            [];

        $postedImagesArray = array_values(array_map(
            static fn (string $json) => json_decode(
                $json,
                true
            ),
            $postedImagesArrayData
        ));

        return PostedImageCollection::fromArray(
            $postedImagesArray,
        );
    }

    /**
     * @param mixed[] $postedFieldData
     */
    private static function deletionsCollectionFromArray(
        array $postedFieldData
    ): PostedDeletionsCollection {
        $postedDeletionsRaw = $postedFieldData['delete_images'] ?? '[]';

        $postedDeletionsArray = json_decode(
        /** @phpstan-ignore-next-line */
            (string) $postedDeletionsRaw,
            true
        );

        $postedDeletionsArray = is_array($postedDeletionsArray) ?
            $postedDeletionsArray :
            [];

        $postedDeletionsMapped = [];

        foreach ($postedDeletionsArray as $id) {
            $postedDeletionsMapped[]['id'] = $id;
        }

        return PostedDeletionsCollection::fromArray(
            $postedDeletionsMapped,
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

    /**
     * @return mixed[]
     */
    public function asScalarArray(): array
    {
        return [
            'images' => $this->postedImages()->asScalarArray(),
            'deletions' =>  $this->postedDeletions()->asScalarArray(),
        ];
    }
}
