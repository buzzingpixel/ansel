<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field\PostedFieldData;

use function array_keys;
use function array_map;
use function array_values;
use function count;

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

    public function count(): int
    {
        return count($this->postedImages);
    }

    /**
     * @param callable(PostedImage $image, int $index): ReturnType $callback
     *
     * @return ReturnType[]
     *
     * @template ReturnType
     */
    public function map(callable $callback): array
    {
        $asArray = $this->asArray();

        return array_values(array_map(
            $callback,
            $asArray,
            array_keys($asArray),
        ));
    }

    /**
     * @return PostedImage[]
     */
    public function asArray(): array
    {
        return $this->postedImages;
    }

    /**
     * @return mixed[]
     */
    public function asScalarArray(): array
    {
        return $this->map(static function (PostedImage $image): array {
            return $image->asScalarArray();
        });
    }
}
