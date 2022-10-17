<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field\PostedFieldData;

use function is_array;

class PostedImage
{
    private string $id;

    private string $imageUrl;

    private string $imageFileName;

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

        $imageUpload = $arrayData['imageUpload'] ?? null;

        return new self(
            /** @phpstan-ignore-next-line */
            (string) ($arrayData['id'] ?? ''),
            /** @phpstan-ignore-next-line */
            (string) ($arrayData['imageUrl'] ?? ''),
            /** @phpstan-ignore-next-line */
            (string) ($arrayData['imageFileName'] ?? ''),
            /** @phpstan-ignore-next-line */
            (string) ($arrayData['sourceImageId'] ?? ''),
            /** @phpstan-ignore-next-line */
            (string) ($arrayData['x'] ?? ''),
            /** @phpstan-ignore-next-line */
            (string) ($arrayData['y'] ?? ''),
            /** @phpstan-ignore-next-line */
            (string) ($arrayData['width'] ?? ''),
            /** @phpstan-ignore-next-line */
            (string) ($arrayData['height'] ?? ''),
            /** @phpstan-ignore-next-line */
            (string) ($arrayData['focalX'] ?? ''),
            /** @phpstan-ignore-next-line */
            (string) ($arrayData['focalY'] ?? ''),
            PostedFieldDataCollection::fromArray(
                $fieldData
            ),
            is_array($imageUpload) ?
                PostedImageUpload::fromArray($imageUpload) :
                null,
        );
    }

    public function __construct(
        string $id,
        string $imageUrl,
        string $imageFileName,
        string $sourceImageId,
        string $x,
        string $y,
        string $width,
        string $height,
        string $focalX,
        string $focalY,
        PostedFieldDataCollection $fieldData,
        ?PostedImageUpload $imageUpload = null
    ) {
        $this->id                        = $id;
        $this->imageUrl                  = $imageUrl;
        $this->imageFileName             = $imageFileName;
        $this->sourceImageId             = $sourceImageId;
        $this->focalX                    = $focalX;
        $this->focalY                    = $focalY;
        $this->x                         = $x;
        $this->y                         = $y;
        $this->width                     = $width;
        $this->height                    = $height;
        $this->postedFieldDataCollection = $fieldData;
        $this->postedImageUpload         = $imageUpload;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function imageUrl(): string
    {
        return $this->imageUrl;
    }

    public function imageFileName(): string
    {
        return $this->imageFileName;
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

    /**
     * @return mixed[]
     */
    public function asScalarArray(): array
    {
        $postedImageUpload = $this->postedImageUpload();

        return [
            'id' => $this->id(),
            'imageUrl' => $this->imageUrl(),
            'imageFileName' => $this->imageFileName(),
            'sourceImageId' => $this->sourceImageId(),
            'x' => $this->x(),
            'y' => $this->y(),
            'width' => $this->width(),
            'height' => $this->height(),
            'focalX' => $this->focalX(),
            'focalY' => $this->focalY(),
            'fieldData' => $this->postedFieldDataCollection()
                ->asScalarArray(),
            'imageUpload' => $postedImageUpload !== null ?
                $postedImageUpload->asScalarArray() :
                null,
        ];
    }
}
