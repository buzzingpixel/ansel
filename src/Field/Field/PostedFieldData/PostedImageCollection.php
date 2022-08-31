<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field\PostedFieldData;

use function array_map;
use function array_values;

class PostedImageCollection
{
    /** @var PostedImage[] */
    private array $postedImages;

    /**
     * @param mixed[] $arrayData
     */
    public static function fromArray(array $arrayData): self
    {
        return new self(array_values(array_map(
            /** @phpstan-ignore-next-line */
            static fn (array $data) => PostedImage::fromArray(
                $data
            ),
            $arrayData,
        )));
    }

    /**
     * @param PostedImage[] $postedImages
     */
    public function __construct(array $postedImages)
    {
        $this->postedImages = array_values(array_map(
            static fn (PostedImage $data) => $data,
            $postedImages,
        ));
    }

    /**
     * @return PostedImage[]
     */
    public function postedImages(): array
    {
        return $this->postedImages;
    }
}
