<?php

namespace BuzzingPixel\Treasury\Service\Image;

class ImageResizeService extends BaseImageService
{
	/**
	 * Properties
	 */
	protected $sourceWidth = 'int';
	protected $sourceHeight = 'int';
	protected $destX = 'int';
	protected $destY = 'int';
	protected $sourceX = 'int';
	protected $sourceY = 'int';
	protected $background = 'string';

	/**
	 * Resize the image
	 *
	 * @return bool
	 */
	public function resize()
	{
		// Preflight items
		if ($preRun = $this->preRun()) {
			return $preRun;
		}

		// Resize the image
		imagecopyresampled(
			$this->newImage, // Destination image
			$this->tmpImage, // Source image
			$this->destX, // Destination x
			$this->destY, // Destination y
			$this->sourceX, // Source x
			$this->sourceY, // Source y
			$this->width, // Desintation width
			$this->height, // Destination height
			$this->sourceWidth, // Source width
			$this->sourceHeight // Source height
		);

		// Write the image to its destination
		$this->writeImageToDestination();

		// Return the resized image path
		return $this->manipulatedImagePath;
	}
}
