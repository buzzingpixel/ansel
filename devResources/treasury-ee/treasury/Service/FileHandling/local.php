<?php

namespace BuzzingPixel\Treasury\Service\FileHandling;

use BuzzingPixel\Treasury\Model\Files;
use BuzzingPixel\Treasury\Service\FileCacheService;

class local extends Base
{
	/**
	 * Upload file
	 *
	 * @param string $locationHandle
	 * @param string $sourcePath
	 * @param string $fileName
	 * @return \BuzzingPixel\Treasury\Model\ValidationResult
	 */
	public function upload($locationHandle, $sourcePath, $fileName)
	{
		// Make sure PHP can write file permissions
		$oldmask = umask(0);

		// Get the Location Model
		$locationModel = self::getLocationModel($locationHandle);

		// Get the ValidationResult model
		$validationResult = self::getValidationResult();

		// Get location and file path
		$locationFilePath = self::getLocalFilePath($fileName, $locationModel);
		$fileName = $locationFilePath['fileName'];
		$path = $locationFilePath['path'];
		$fullFilePath = $locationFilePath['fullFilePath'];

		// Make sure file path exists
		if (! is_dir($path)) {
			mkdir($path, 0777, true);
		}

		// Check if this file exists
		if (file_exists($fullFilePath)) {
			// Reset the umask
			umask($oldmask);

			// Set errors
			$validationResult->addError(lang('file_name_exists'));

			// Return the results
			return $validationResult;
		}

		// Copy the file
		$success = copy($sourcePath, $fullFilePath);

		// If we do not have success, set an error
		if (! $success) {
			$validationResult->addError(lang('directory_not_writable'));
		}

		// Reset the umask
		umask($oldmask);

		// Return the results
		return $validationResult;
	}

	/**
	 * Delete file
	 *
	 * @param string $locationHandle
	 * @param string $fileName
	 */
	public function deleteFile($locationHandle, $fileName)
	{
		// Get the Location Model
		$locationModel = self::getLocationModel($locationHandle);

		// Get location and file path
		$locationFilePath = self::getLocalFilePath($fileName, $locationModel);
		$fileName = $locationFilePath['fileName'];
		$path = $locationFilePath['path'];
		$fullFilePath = $locationFilePath['fullFilePath'];

		// Delete file
		if (is_file($fullFilePath)) {
			unlink($fullFilePath);
		}
	}

	/**
	 * Check if file exists
	 *
	 * @param string $locationHandle
	 * @param string $fileName
	 * @return bool
	 */
	public function fileExists($locationHandle, $fileName)
	{
		// Get the Location Model
		$locationModel = self::getLocationModel($locationHandle);

		// Get location and file path
		$locationFilePath = self::getLocalFilePath($fileName, $locationModel);
		$fileName = $locationFilePath['fileName'];
		$path = $locationFilePath['path'];
		$fullFilePath = $locationFilePath['fullFilePath'];

		return file_exists($fullFilePath);
	}

	/**
	 * Cache file
	 *
	 * @param \BuzzingPixel\Treasury\Model\Files
	 * @return string
	 */
	public function getFileAndCache(Files $fileModel)
	{
		return FileCacheService::cacheByPath($fileModel->location->path . $fileModel->file_name);
	}
}
