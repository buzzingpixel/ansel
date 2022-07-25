<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field\PostedFieldData;

use function array_map;
use function array_values;

class PostedImageCollection
{
    /**
     * @param mixed[] $postedImages
     */
    public static function fromArray(array $postedImages): self
    {
        return new self(array_values(array_map(
            /** @phpstan-ignore-next-line */
            static function (array $postedImage) {
                return PostedImage::fromArray($postedImage);
            },
            $postedImages,
        )));
    }

    /** @var PostedImage[] */
    private array $postedImages;

    /**
     * @param PostedImage[] $postedImages
     */
    public function __construct(array $postedImages)
    {
        $this->postedImages = array_values(array_map(
            static function (PostedImage $postedImage) {
                return $postedImage;
            },
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
