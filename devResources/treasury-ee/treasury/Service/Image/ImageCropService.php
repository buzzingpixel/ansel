<?php

namespace BuzzingPixel\Treasury\Service\Image;

class ImageCropService extends BaseImageService
{
	/**
	 * Properties
	 */
	protected $cropWidth = 'int';
	protected $cropHeight = 'int';
	protected $cropX = 'int';
	protected $cropY = 'int';

	/**
	 * Crop the image
	 *
	 * @return bool
	 */
	public function crop()
	{
		// Preflight items
		if ($preRun = $this->preRun()) {
			return $preRun;
		}

		// Crop the image
		imagecopy(
			$this->newImage,
			$this->tmpImage,
			0,
			0,
			$this->cropX,
			$this->cropY,
			$this->cropWidth,
			$this->cropHeight
		);

		// Write the image to its destination
		$this->writeImageToDestination();

		// Return the cropped image path
		return $this->manipulatedImagePath;
	}
}
