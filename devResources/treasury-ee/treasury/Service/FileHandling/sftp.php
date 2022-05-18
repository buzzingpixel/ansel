<?php

namespace BuzzingPixel\Treasury\Service\FileHandling;

use BuzzingPixel\Treasury\Model\Files;
use BuzzingPixel\Treasury\Service\FileCacheService;

class sftp extends Base
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
		// Get the ValidationResult model
		$validationResult = self::getValidationResult();

		// Start errors array
		$errors = array();

		// Get the Location Model
		$locationModel = self::getLocationModel($locationHandle);

		// Get location and file path
		$locationFilePath = self::getFtpFilePath($fileName, $locationModel);

		// Log in to the server and check if successful
		// if not successful, we need to return an error
		$sftp = self::sftpLogin($locationModel);
		if (! $sftp) {
			// Set error
			$validationResult->addError(lang('server_configuration_incorrect'));

			// Return the results
			return $validationResult;
		}

		// Check the file size to see if the file exists
		if ($sftp->size($locationFilePath['fullFilePath'])) {
			// Set error
			$validationResult->addError(lang('file_name_exists'));

			// Return the results
			return $validationResult;
		}

		// Loop through the directories
		$joinedPath = '';
		foreach ($locationFilePath['pathArr'] as $path) {
			// If the path is empty, continue to the next array item
			if (! $path) {
				continue;
			}

			// Add to the path
			$joinedPath .= "/{$path}";

			// Change dir to the current path
			$sftp->chdir($joinedPath);

			// Check if changing to the path was successful
			// if not, create the directory
			if ($sftp->pwd() !== $joinedPath) {
				$sftp->mkdir($joinedPath);
			}
		}

		// Upload the file
		$uploadSuccess = $sftp->put(
			$locationFilePath['fullFilePath'],
			$sourcePath,
			NET_SFTP_LOCAL_FILE
		);

		// If the upload was not successful, set error
		if (! $uploadSuccess) {
			$validationResult->addError(lang('unable_to_upload_file'));
		}

		// Set errors
		$validationResult->addErrors($errors);

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
		$locationFilePath = self::getFtpFilePath($fileName, $locationModel);

		// Log in to SFTP
		$sftp = self::sftpLogin($locationModel);
		if (! $sftp) {
			return;
		}

		// Check the file size to see if the file exists
		if ($sftp->size($locationFilePath['fullFilePath'])) {
			$sftp->delete($locationFilePath['fullFilePath']);
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
		$locationFilePath = self::getFtpFilePath($fileName, $locationModel);

		// Log in to SFTP
		$sftp = self::sftpLogin($locationModel);
		if (! $sftp) {
			return false;
		}

		// Check the file size to see if the file exists
		if ($sftp->size($locationFilePath['fullFilePath'])) {
			return true;
		}

		// File does not exist
		return false;
	}

	/**
	 * Cache file
	 *
	 * @param \BuzzingPixel\Treasury\Model\Files
	 * @return string
	 */
	public function getFileAndCache(Files $fileModel)
	{
		$cacheFilePath = FileCacheService::createEmptyFile($fileModel->extension);

		// Get location and file path
		$locationFilePath = self::getFtpFilePath($fileModel->file_name, $fileModel->location);

		// Log in to the server and check if successful
		// if not successful, we need to return an error
		$sftp = self::sftpLogin($fileModel->location);
		if (! $sftp) {
			return '';
		}

		$sftp->get(
			$locationFilePath['fullFilePath'],
			$cacheFilePath
		);

		return $cacheFilePath;
	}
}
