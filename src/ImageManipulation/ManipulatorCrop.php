<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\ImageManipulation;

use Intervention\Image\Image;

class ManipulatorCrop
{
    public function runCrop(
        Image $image,
        Parameters $parameters
    ): Image {
        if (! $this->shouldCrop($image, $parameters)) {
            return $image;
        }

        return $image->crop(
            $parameters->getWidth() ?? $image->width(),
            $parameters->getHeight() ?? $image->height(),
            $parameters->getX() ?? 0,
            $parameters->getY() ?? 0,
        );
    }

    private function shouldCrop(
        Image $image,
        Parameters $parameters
    ): bool {
        $width = $parameters->getWidth();

        $height = $parameters->getHeight();

        return ($width !== null && $width < $image->width()) ||
            ($height !== null && $height < $image->height());
    }
}
