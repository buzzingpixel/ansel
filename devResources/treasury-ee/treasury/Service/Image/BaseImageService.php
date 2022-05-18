<?php

namespace BuzzingPixel\Treasury\Service\Image;

use BuzzingPixel\Treasury\Utility\ConvertHex;
use BuzzingPixel\Treasury\Service\FileCacheService;

class BaseImageService extends \BuzzingPixel\Treasury\Service\BaseService
{
	protected $sourceFilePath = 'string';
	protected $sourceFileType = 'int';
	protected $targetFileType = 'int';
	protected $width = 'int';
	protected $height = 'int';
	protected $tmpImage = false;
	protected $newImage = false;
	protected $quality = 'int';
	protected $manipulatedImagePath = 'string';

	/**
	 * Magic set method
	 *
	 * @param string $name
	 * @param mixed $val
	 */
	public function __set($name, $val)
	{
		// Run parent setter
		parent::__set($name, $val);

		// Don’t allow class overloading
		return null;
	}

	/**
	 * Check properties
	 *
	 * @return bool
	 */
	protected function checkProperties()
	{
		// Set the quality if needed
		if (! $this->quality) {
			$this->quality = 100;
		}

		return true;
	}

	/**
	 * Pre run
	 */
	protected function preRun()
	{
		// Check if the image has already been cropped
		if ($this->manipulatedImagePath) {
			return $this->manipulatedImagePath;
		}

		// Check properties
		if (! $this->checkProperties()) {
			return false;
		}

		// Create the tmpImage Resource
		if (! $this->createTmpImageResource()) {
			return false;
		}

		// Set width and height if necesary
		if (! $this->width && ! $this->height) {
			$imageSize = getimagesize($this->sourceFilePath);
			$this->width = $imageSize[0];
			$this->height = $imageSize[1];
		}

		// Create the new image resource
		$this->createImage();

		return null;
	}

	/**
	 * Create tmpImage resource
	 *
	 * @return bool
	 */
	protected function createTmpImageResource()
	{
		// Make sure source filetype has been set
		if (! $this->sourceFileType) {
			$this->setSourceFileType();
		}

		// Create the correct image resource based on file type
		if ($this->sourceFileType === 1) {
			$this->tmpImage = imagecreatefromgif($this->sourceFilePath);
		} elseif ($this->sourceFileType === 2) {
			$this->tmpImage = imagecreatefromjpeg($this->sourceFilePath);
		} elseif ($this->sourceFileType === 3) {
			$this->tmpImage = imagecreatefrompng($this->sourceFilePath);
		} else {
			return false;
		}

		return true;
	}

	/**
	 * Set source file type
	 */
	protected function setSourceFileType()
	{
		if (file_exists($this->sourceFilePath)) {
			$fileSize = getimagesize($this->sourceFilePath);
			$this->sourceFileType = $fileSize[2];
		} else {
			$this->sourceFileType = 0;
		}

		// Set the target file type if needed
		if ($this->targetFileType === null) {
			$this->targetFileType = $this->sourceFileType;
		}
	}

	/**
	 * Write image to destination
	 */
	protected function writeImageToDestination()
	{
		$filePath = false;

		// Write image
		if ($this->targetFileType === 1) {
			$filePath = FileCacheService::createEmptyFile('gif');
			imagegif($this->newImage, $filePath);
		} elseif ($this->targetFileType === 2) {
			$filePath = FileCacheService::createEmptyFile('jpg');
			imagejpeg($this->newImage, $filePath, $this->quality);
		} elseif ($this->targetFileType === 3) {
			$filePath = FileCacheService::createEmptyFile('png');
			imagepng($this->newImage, $filePath, 9);
		}

		// Free up memory
		imagedestroy($this->newImage);
		imagedestroy($this->tmpImage);
		$this->newImage = null;
		$this->tmpImage = null;

		// Set the manipulatedImagePath
		$this->manipulatedImagePath = $filePath;
	}

	/**
	 * Create image and fill background appropriately
	 */
	protected function createImage()
	{
		// Create the new image resource
		$this->newImage = imagecreatetruecolor(
			$this->width, $this->height
		);

		// Set the image background color
		if ($this->targetFileType === 2) {
			$color = $this->background ?: 'ffffff';

			$rgb = ConvertHex::process($color);

			$background = imagecolorallocate(
				$this->newImage,
				$rgb['r'],
				$rgb['g'],
				$rgb['b']
			);

			imagefill($this->newImage, 0, 0, $background);
		} else {
			if ($this->background) {
				$rgb = ConvertHex::process($this->background);

				imagefilter(
					$this->newImage,
					IMG_FILTER_COLORIZE,
					$rgb['r'],
					$rgb['g'],
					$rgb['b']
				);
			} else {
				$transparent = imagecolortransparent(
					$this->newImage,
					imagecolorallocatealpha($this->newImage, 0, 0, 0, 0)
				);

				if ($this->targetFileType === 3) {
					imagealphablending($this->newImage, false);
					imagesavealpha($this->newImage, true);
				}

				imagefill($this->newImage, 0, 0, $transparent);
			}
		}
	}
}
