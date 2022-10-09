<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\ImageManipulation;

use Intervention\Image\Image;

use function round;

class ManipulatorUpscale
{
    public function runUpscale(
        Image $image,
        Parameters $parameters
    ): Image {
        if (! $this->shouldUpscale($image, $parameters)) {
            return $image;
        }

        $minWidth = $parameters->getMinWidth() ?? $image->getWidth();

        $minHeight = $parameters->getMinHeight() ?? $image->getHeight();

        // Start with width and make sure min height meets
        $dimensions = $this->calculateUpscaleByWidth(
            $image,
            $parameters,
        );

        // Check if height meets requirements
        if ($dimensions['height'] < $minHeight) {
            // Calculate dimensions by height
            $dimensions = $this->calculateUpscaleByHeight(
                $image,
                $parameters,
            );
        }

        // Because of pixel rounding, determine how close width is and set
        // to min if within tolerance
        $difference = 0;
        if ($dimensions['width'] > $minWidth) {
            $difference = $dimensions['width'] - $minWidth;
        } elseif ($minWidth > $dimensions['width']) {
            $difference = $minWidth - $dimensions['width'];
        }

        if ($difference < 4) {
            $dimensions['width'] = $minWidth;
        }

        // Because of pixel rounding, determine how close height is and set
        // to min if within tolerance
        $difference = 0;
        if ($dimensions['height'] > $minHeight) {
            $difference = $dimensions['height'] - $minHeight;
        } elseif ($minHeight > $dimensions['height']) {
            $difference = $minHeight - $dimensions['height'];
        }

        if ($difference < 4) {
            $dimensions['height'] = $minHeight;
        }

        return $image->resize(
            $dimensions['width'],
            $dimensions['height'],
        );
    }

    private function shouldUpscale(
        Image $image,
        Parameters $parameters
    ): bool {
        $minWidth = $parameters->getMinWidth();

        $minHeight = $parameters->getMinHeight();

        return ($minWidth !== null && $image->width() < $minWidth) ||
            ($minHeight !== null && $image->height() < $minHeight);
    }

    /**
     * @return array{
     *     ratio: float,
     *     width: int,
     *     height: int,
     * }
     */
    private function calculateUpscaleByWidth(
        Image $image,
        Parameters $parameters
    ): array {
        $imageWidth = $image->getWidth();

        $imageHeight = $image->getHeight();

        $width = $parameters->getWidth();

        $minWidth = $parameters->getMinWidth();

        $ratio = (float) ($minWidth ?? $width ?? $imageWidth) /
            ($width ?? $imageWidth);

        return [
            'ratio' => $ratio,
            'width' => $minWidth ?? $imageWidth,
            'height' => (int) round(
                ($parameters->getHeight() ?? $imageHeight) * $ratio,
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
    private function calculateUpscaleByHeight(
        Image $image,
        Parameters $parameters
    ): array {
        $imageWidth = $image->getWidth();

        $imageHeight = $image->getHeight();

        $height = $parameters->getHeight();

        $minHeight = $parameters->getMinHeight();

        $ratio = (float) ($minHeight ?? $height ?? $imageHeight) /
            ($height ?? $imageHeight);

        return [
            'ratio' => $ratio,
            'height' => $minHeight ?? $imageHeight,
            'width' => (int) round(
                ($parameters->getWidth() ?? $imageWidth) * $ratio,
            ),
        ];
    }
}
