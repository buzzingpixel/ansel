<?php

namespace BuzzingPixel\Treasury\Service\FileHandling;

use \BuzzingPixel\Treasury\Model\Locations;
use BuzzingPixel\Treasury\Model\Files;
use BuzzingPixel\Treasury\Service\LocationsService;
use BuzzingPixel\Treasury\Model\ValidationResult;
use BuzzingPixel\Treasury\Service\Config;

abstract class Base
{
	/**
	 * Upload file
	 *
	 * @param string $locationHandle
	 * @param string $sourcePath
	 * @param string $fileName
	 * @return \BuzzingPixel\Treasury\Model\ValidationResult
	 */
	abstract protected function upload($locationHandle, $sourcePath, $fileName);

	/**
	 * Delete file
	 *
	 * @param string $locationHandle
	 * @param string $fileName
	 */
	abstract protected function deleteFile($locationHandle, $fileName);

	/**
	 * Check if file exists
	 *
	 * @param string $locationHandle
	 * @param string $fileName
	 * @return bool
	 */
	abstract protected function fileExists($locationHandle, $fileName);

	/**
	 * Cache file
	 *
	 * @param \BuzzingPixel\Treasury\Model\Files
	 * @return string
	 */
	abstract protected function getFileAndCache(Files $fileModel);

	/**
	 * Get location model
	 *
	 * @param string $locationHandle
	 */
	protected static function getLocationModel($locationHandle)
	{
		// Get the Location Model
		$locationsService = LocationsService::getInstance();
		return $locationsService->{$locationHandle};
	}

	/**
	 * Get validation result
	 */
	protected static function getValidationResult()
	{
		return new ValidationResult();
	}

	/**
	 * Get local file path
	 *
	 * @param string $fileName
	 * @param \BuzzingPixel\Treasury\Model\Locations $locationModel
	 * @return array
	 */
	protected static function getLocalFilePath(
		$fileName,
		Locations $locationModel
	)
	{
		// Get the upload path
		$path = explode(DIRECTORY_SEPARATOR, rtrim(
			$locationModel->path,
			DIRECTORY_SEPARATOR
		));

		// Get file path (in case filename includes any directories)
		$filePath = explode(DIRECTORY_SEPARATOR, $fileName);

		// Get the real filename
		$fileName = $filePath[count($filePath) - 1];

		// Unset the file name
		unset($filePath[count($filePath) - 1]);

		// Merge filePath and path to get final path
		foreach ($filePath as $val) {
			$path[] = $val;
		}

		// Put the file path back together
		$path = implode(DIRECTORY_SEPARATOR, $path);

		// Return the results
		return array(
			'path' => $path,
			'fileName' => $fileName,
			'fullFilePath' => $path . DIRECTORY_SEPARATOR . $fileName
		);
	}

	/**
	 * Get ftp file path
	 *
	 * @param string $fileName
	 * @param \BuzzingPixel\Treasury\Model\Locations $locationModel
	 * @return array
	 */
	protected static function getFtpFilePath(
		$fileName,
		Locations $locationModel
	)
	{
		// Get the upload path
		$path = explode('/', rtrim($locationModel->remote_path, '/'));

		// Get file path (in case filename includes any directories)
		$filePath = explode('/', $fileName);

		// Get the real filename
		$fileName = $filePath[count($filePath) - 1];

		// Unset the file name
		unset($filePath[count($filePath) - 1]);

		// Merge filePath and path to get final path
		foreach ($filePath as $val) {
			$path[] = $val;
		}

		// Put the file path back together
		$joinedPath = implode('/', $path);

		// Return the results
		return array(
			'pathArr' => $path,
			'path' => $joinedPath,
			'fileName' => $fileName,
			'fullFilePath' => "{$joinedPath}/{$fileName}"
		);
	}

	/**
	 * Get s3 client
	 *
	 * @param \BuzzingPixel\Treasury\Model\Locations $locationModel
	 * @return \Aws\S3\S3Client
	 */
	protected static function getS3Client(
		Locations $locationModel
	)
	{
		return new \Aws\S3\S3Client([
			'version' => 'latest',
			'region' => $locationModel->bucket_region,
			'credentials' => array(
				'key' => $locationModel->access_key_id,
				'secret' => $locationModel->secret_access_key
			)
		]);
	}

	/**
	 * Get s3 key
	 *
	 * @param string $fileName
	 * @param \BuzzingPixel\Treasury\Model\Locations $locationModel
	 * @return string
	 */
	protected static function getS3Key(
		$fileName,
		Locations $locationModel
	)
	{
		// Set object key
		$key = $fileName;

		// If there is a sub folder, we need to use that in front of filename
		if ($locationModel->subfolder) {
			$subFolder = ltrim(rtrim($locationModel->subfolder, '/'), '/') . '/';

			$key = $subFolder . $fileName;
		}

		return $key;
	}

	/**
	 * SFTP login
	 *
	 * @param \BuzzingPixel\Treasury\Model\Locations $locationModel
	 * @return false|\Net_SFTP
	 */
	protected static function sftpLogin(
		Locations $locationModel
	)
	{
		// Set initial result variable
		$loginResult = false;

		// Make sure we have a server, port, and username
		if (
			! $locationModel->server ||
			! $locationModel->port ||
			! $locationModel->username
		) {
			return false;
		}

		// Get the SFTP library
		$sftp = new \Net_SFTP($locationModel->server, $locationModel->port);

		// Check for private key
		if (
			$locationModel->private_key ||
			$locationModel->private_key_path ||
			$locationModel->use_config_private_key_path
		) {
			// Get the RSA class
			$rsa = new \Crypt_RSA();

			// Set key password if applicable
			if ($locationModel->password) {
				$rsa->setPassword($locationModel->password);
			}

			// Load the key
			if ($locationModel->use_config_private_key_path) {
				$keyLoadSuccess = $rsa->loadKey(file_get_contents(
					Config::get('private_key_path')
				));
			} elseif (
				$locationModel->private_key_path &&
				is_file($locationModel->private_key_path)
			) {
				$keyLoadSuccess = $rsa->loadKey(file_get_contents(
					$locationModel->private_key_path
				));
			} else {
				$keyLoadSuccess = $rsa->loadKey($locationModel->private_key);
			}

			if (! $keyLoadSuccess) {
				return false;
			}

			// Try to log in
			$loginResult = $sftp->login($locationModel->username, $rsa);
		} elseif ($locationModel->password) {
			$loginResult = $sftp->login($locationModel->username, $locationModel->password);
		}

		// Check if we successfully logged in
		if ($loginResult) {
			return $sftp;
		}

		// Return false because login was not successful
		return false;
	}

	/**
	 * FTP login
	 *
	 * @param \BuzzingPixel\Treasury\Model\Locations $locationModel
	 * @return bool|resource
	 */
	protected static function ftpLogin(
		Locations $locationModel
	)
	{
		// Make sure we have a server, port, and username and password
		if (
			! $locationModel->server ||
			! $locationModel->port ||
			! $locationModel->username ||
			! $locationModel->password
		) {
			return false;
		}

		// Connect to the server
		$connId = ftp_connect($locationModel->server, $locationModel->port);

		// Log in to server, if fail, return false
		if (
			! ftp_login(
				$connId,
				$locationModel->username,
				$locationModel->password
			)
		) {
			return false;
		}

		// Put FTP in passive mode
		ftp_pasv($connId, true);

		// Return the connection ID
		return $connId;
	}
}
