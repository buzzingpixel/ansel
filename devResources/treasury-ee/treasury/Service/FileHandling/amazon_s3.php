<?php

namespace BuzzingPixel\Treasury\Service\FileHandling;

use BuzzingPixel\Treasury\Model\Files;
use BuzzingPixel\Treasury\Service\FileCacheService;

class amazon_s3 extends Base
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

		// Get S3 Client
		$s3 = self::getS3Client($locationModel);

		// Set object key
		$key = self::getS3Key($fileName, $locationModel);

		// Check if this file exists
		if ($s3->doesObjectExist($locationModel->bucket, $key)) {
			// Set errors
			$validationResult->addError(lang('file_name_exists'));

			// Return the results
			return $validationResult;
		}

		// Upload the file and catch any errors
		try {
			$s3->putObject(array(
				'Bucket' => $locationModel->bucket,
				'Key' => $key,
				'SourceFile' => $sourcePath,
				'ACL' => 'public-read',
				'ContentType' => mime_content_type($sourcePath),
			));
		} catch (\Aws\S3\Exception\S3Exception $e) {
			if (
				$e->getAwsErrorCode() === 'SignatureDoesNotMatch' ||
				$e->getAwsErrorCode() === 'InvalidAccessKeyId'
			) {
				$errors[] = lang('s3_invalid_credentials');
			} elseif ($e->getAwsErrorCode() === 'AllAccessDisabled') {
				$errors[] = lang('s3_all_access_disabled');
			} elseif ($e->isConnectionError()) {
				$errors[] = lang('s3_connection_error');
			} elseif (gettype($e->getMessage()) === 'string') {
				$errors[] = $e->getMessage();
			}
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
	 * @return \BuzzingPixel\Treasury\Model\ValidationResult
	 */
	public function deleteFile($locationHandle, $fileName)
	{
		// Get the Location Model
		$locationModel = self::getLocationModel($locationHandle);

		// Get the ValidationResult model
		$validationResult = self::getValidationResult();

		// Start errors array
		$errors = array();

		// Get S3 Client
		$s3 = self::getS3Client($locationModel);

		// Set object key
		$key = self::getS3Key($fileName, $locationModel);

		// Check if this file exists
		if ($s3->doesObjectExist($locationModel->bucket, $key)) {
			// Delete the file and catch any errors
			try {
				$s3->deleteObject(array(
					'Bucket' => $locationModel->bucket,
					'Key' => $key
				));
			} catch (\Aws\S3\Exception\S3Exception $e) {
				if (
					$e->getAwsErrorCode() === 'SignatureDoesNotMatch' ||
					$e->getAwsErrorCode() === 'InvalidAccessKeyId'
				) {
					$errors[] = lang('s3_invalid_credentials');
				} elseif ($e->getAwsErrorCode() === 'AllAccessDisabled') {
					$errors[] = lang('s3_all_access_disabled');
				} elseif ($e->isConnectionError()) {
					$errors[] = lang('s3_connection_error');
				} elseif (gettype($e->getMessage()) === 'string') {
					$errors[] = $e->getMessage();
				}
			}
		}

		// Set errors
		$validationResult->addErrors($errors);

		// Return the results
		return $validationResult;
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

		// Get S3 Client
		$s3 = self::getS3Client($locationModel);

		// Set object key
		$key = self::getS3Key($fileName, $locationModel);

		return $s3->doesObjectExist($locationModel->bucket, $key);
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

		// Get S3 Client
		$s3 = self::getS3Client($fileModel->location);

		// Set object key
		$key = self::getS3Key($fileModel->file_name, $fileModel->location);

		$s3->getObject(array(
			'Bucket' => $fileModel->location->bucket,
			'Key' => $key,
			'SaveAs' => $cacheFilePath
		));

		return $cacheFilePath;
	}
}
