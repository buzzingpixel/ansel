<?php

namespace BuzzingPixel\Treasury\Service\Image;

use BuzzingPixel\Treasury\Service\BaseService;

class ThumbnailService extends BaseService
{
	const THUMB_WIDTH = 73;
	const THUMB_HEIGHT = 60;

	// Source Image
	private $srcImage;

	/**
	 * Constructor
	 *
	 * @param object $srcImage Basic image object
	 */
	public function __construct(\BuzzingPixel\Treasury\Model\BasicImage $srcImage)
	{
		// Set the source image
		$this->srcImage = $srcImage;

		// Run parent constructor
		parent::__construct();
	}

	/**
	 * Get/Run the image manipulation
	 *
	 * @return object
	 */
	public function get()
	{
		$manipulated = false;

		// Get/Cache the source file
		$src = $this->srcImage->location;

		// Make sure requirements are met
		if (! $src) {
			return null;
		}

		// Calculate resize by width
		$ratio = (float) self::THUMB_WIDTH / $this->srcImage->width;
		$resizeWidth = self::THUMB_WIDTH;
		$resizeHeight = (int) round($this->srcImage->height * $ratio);

		// Check if height is under thumb height
		if ($resizeHeight < self::THUMB_HEIGHT) {
			$resizeHeight = self::THUMB_HEIGHT;
			$ratio = $ratio = (float) self::THUMB_HEIGHT /  $this->srcImage->height;
			$resizeWidth = (int) round($this->srcImage->width * $ratio);
		}

		// If the height is too tall, get resize by height
		if ($this->maxHeight && $resizeDimensions['height'] > $this->maxHeight) {
			$resizeDimensions = $this->calcResizeByHeight();
		}

		// Get the resizing service
		$resizeImage = new ImageResizeService();

		// Set the source values
		$resizeImage->sourceFilePath = $src;
		$resizeImage->sourceWidth = $this->srcImage->width;
		$resizeImage->sourceHeight = $this->srcImage->height;

		// Set resize values
		$resizeImage->width = $resizeWidth;
		$resizeImage->height = $resizeHeight;

		// Resize the image
		$src = $resizeImage->resize();

		// Make sure memory gets freed up
		$resizeImage = null;

		// Check if cropping is needed
		if (
			$resizeWidth > self::THUMB_WIDTH ||
			$resizeHeight > self::THUMB_HEIGHT
		) {
			// Get the croping service
			$cropImage = new ImageCropService();

			// Set image width and height
			$cropImage->width = self::THUMB_WIDTH;
			$cropImage->height = self::THUMB_HEIGHT;

			// Set the source values
			$cropImage->sourceFilePath = $src;

			// Set initial cropX
			$cropX = 0;
			$cropY = 0;

			if ($resizeWidth > self::THUMB_WIDTH) {
				$cropX = ($resizeWidth - self::THUMB_WIDTH) / 2;
			}

			if ($resizeHeight > self::THUMB_HEIGHT) {
				$cropY = ($resizeHeight - self::THUMB_HEIGHT) / 2;
			}

			// Set crop values
			$cropImage->cropX = $this->cropX;
			$cropImage->cropY = $this->cropY;
			$cropImage->cropWidth = self::THUMB_WIDTH;
			$cropImage->cropHeight = self::THUMB_HEIGHT;

			// Crop the image
			$src = $cropImage->crop();

			// Make sure memory gets freed up
			$cropImage = null;
		}

		// Set the final image width and height
		$imageSize = getimagesize($src);
		$this->finalWidth = $imageSize[0];
		$this->finalHeight = $imageSize[1];
		$this->finalImageType = $imageSize[2];

		return $src;
	}
}
