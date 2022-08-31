<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field\PostedFieldData;

use function is_array;

class PostedImage
{
    private string $id;

    private string $sourceImageId;

    private string $focalX;

    private string $focalY;

    private string $x;

    private string $y;

    private string $width;

    private string $height;

    private PostedFieldDataCollection $postedFieldDataCollection;

    private ?PostedImageUpload $postedImageUpload;

    /**
     * @param mixed[] $arrayData
     */
    public static function fromArray(array $arrayData): self
    {
        $fieldData = $arrayData['fieldData'] ?? [];

        $fieldData = is_array($fieldData) ? $fieldData : [];

        $imageUploadData = $arrayData['imageUpload'] ?? null;

        return new self(
            /** @phpstan-ignore-next-line */
            (string) ($arrayData['id'] ?? ''),
            /** @phpstan-ignore-next-line */
            (string) ($arrayData['sourceImageId'] ?? ''),
            /** @phpstan-ignore-next-line */
            (string) ($arrayData['focalX'] ?? ''),
            /** @phpstan-ignore-next-line */
            (string) ($arrayData['focalY'] ?? ''),
            /** @phpstan-ignore-next-line */
            (string) ($arrayData['x'] ?? ''),
            /** @phpstan-ignore-next-line */
            (string) ($arrayData['y'] ?? ''),
            /** @phpstan-ignore-next-line */
            (string) ($arrayData['width'] ?? ''),
            /** @phpstan-ignore-next-line */
            (string) ($arrayData['height'] ?? ''),
            PostedFieldDataCollection::fromArray(
                $fieldData
            ),
            is_array($imageUploadData) ?
                PostedImageUpload::fromArray($imageUploadData) :
                null,
        );
    }

    public function __construct(
        string $id,
        string $sourceImageId,
        string $focalX,
        string $focalY,
        string $x,
        string $y,
        string $width,
        string $height,
        PostedFieldDataCollection $postedFieldDataCollection,
        ?PostedImageUpload $postedImageUpload
    ) {
        $this->id                        = $id;
        $this->sourceImageId             = $sourceImageId;
        $this->focalX                    = $focalX;
        $this->focalY                    = $focalY;
        $this->x                         = $x;
        $this->y                         = $y;
        $this->width                     = $width;
        $this->height                    = $height;
        $this->postedFieldDataCollection = $postedFieldDataCollection;
        $this->postedImageUpload         = $postedImageUpload;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function sourceImageId(): string
    {
        return $this->sourceImageId;
    }

    public function focalX(): string
    {
        return $this->focalX;
    }

    public function focalY(): string
    {
        return $this->focalY;
    }

    public function x(): string
    {
        return $this->x;
    }

    public function y(): string
    {
        return $this->y;
    }

    public function width(): string
    {
        return $this->width;
    }

    public function height(): string
    {
        return $this->height;
    }

    public function postedFieldDataCollection(): PostedFieldDataCollection
    {
        return $this->postedFieldDataCollection;
    }

    public function postedImageUpload(): ?PostedImageUpload
    {
        return $this->postedImageUpload;
    }
}
