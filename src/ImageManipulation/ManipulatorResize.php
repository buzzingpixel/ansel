<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\ImageManipulation;

use Intervention\Image\Image;

use function round;

class ManipulatorResize
{
    public function runResize(
        Image $image,
        Parameters $parameters
    ): Image {
        if (! $this->shouldResize($image, $parameters)) {
            return $image;
        }

        $maxWidth = $parameters->getMaxWidth();

        $maxHeight = $parameters->getMaxHeight();

        // Calculate resize by width
        $dimensions = $this->calculateResizeByWidth(
            $image,
            $parameters,
        );

        // Make sure height meets requirements this way
        if (
            $maxWidth !== null ||
            $maxHeight !== null && $dimensions['height'] > $maxHeight
        ) {
            // Calculate dimensions by height if height is over
            $dimensions = $this->calculateResizeByHeight(
                $image,
                $parameters,
            );
        }

        return $image->resize(
            $dimensions['width'],
            $dimensions['height'],
        );
    }

    private function shouldResize(Image $image, Parameters $parameters): bool
    {
        $maxWidth = $parameters->getMaxWidth();

        $maxHeight = $parameters->getMaxHeight();

        return ($maxWidth !== null && $image->width() > $maxWidth) ||
            ($maxHeight !== null && $image->height() > $maxHeight);
    }

    /**
     * @return array{
     *     ratio: float,
     *     width: int,
     *     height: int,
     * }
     */
    private function calculateResizeByWidth(
        Image $image,
        Parameters $parameters
    ): array {
        $imageWidth = $image->getWidth();

        $imageHeight = $image->getHeight();

        $width = $parameters->getWidth();

        $height = $parameters->getHeight();

        $maxWidth = $parameters->getMaxWidth();

        $ratio = (float) ($maxWidth ?? $width ?? $imageWidth) /
            ($width ?? $imageWidth);

        return [
            'ratio' => $ratio,
            'width' => $maxWidth ?? $imageWidth,
            'height' => (int) round(
                ($height ?? $imageHeight) * $ratio,
            ),
        ];
    }

    /**
     * @return array{
     *     ratio: float,
     *     width: int,
     *     height: int,
     * }
     */
    private function calculateResizeByHeight(
        Image $image,
        Parameters $parameters
    ): array {
        $imageWidth = $image->getWidth();

        $imageHeight = $image->getHeight();

        $width = $parameters->getWidth();

        $height = $parameters->getHeight();

        $maxHeight = $parameters->getMaxHeight();

        $ratio = (float) ($maxHeight ?? $height ?? $imageHeight) /
            ($height ?? $imageHeight);

        return [
            'ratio' => $ratio,
            'height' => $maxHeight ?? $imageHeight,
            'width' => (int) round(
                ($width ?? $imageWidth) * $ratio
            ),
        ];
    }
}
