<?php

namespace BuzzingPixel\Treasury\API;

use BuzzingPixel\Treasury\Service\LocationsService;
use BuzzingPixel\Treasury\Model\ValidationResult;
use BuzzingPixel\Treasury\Record\Files as FilesRecord;
use BuzzingPixel\Treasury\Service\FileCacheService;
use BuzzingPixel\Treasury\Model\BasicImage;
use BuzzingPixel\Treasury\Service\Image\ThumbnailService;

class Upload extends BaseAPI
{
	// Internal class vars
	private $bypassUploadValidation = false;

	// Setable class vars
	protected $locationHandle = 'string';
	protected $filePath = 'string';
	protected $fileName = 'string';
	protected $title = 'string';
	protected $description = 'string';

	/**
	 * Add a file to treasury
	 */
	public function addFile()
	{
		// Validate upload
		$validationResult = $this->validateFileForUpload();

		// Check if validation failed
		if ($validationResult->hasErrors) {
			return $validationResult;
		}

		// Get the Location Model
		$locationsService = LocationsService::getInstance();
		$locationModel = $locationsService->{$this->locationHandle};

		// Load the mime_type library
		ee()->load->library('mime_type');

		// Set file name
		if ($this->fileName) {
			$fileName = ee('Security/XSS')->clean($this->fileName);
		} else {
			$fileName = ee('Security/XSS')->clean(basename($this->filePath));
		}

		// Set title if necesary
		$title = $this->title;
		if (! $title) {
			$title = $fileName;
		}

		// Set if file is image
		$fileIsImage = ee()->mime_type->fileIsImage($this->filePath);

		// Get file extension info
		$fileInfo = pathinfo($fileName);

		// Cache with file extension if applicable
		if (isset($fileInfo['extension'])) {
			$filePath = FileCacheService::cacheByPath(
				$this->filePath,
				$fileInfo['extension']
			);
		} else {
			$filePath = FileCacheService::cacheByPath($this->filePath);
		}

		// Get a new file record
		$record = new FilesRecord();

		/**
		 * Set record properties
		 */

		$record->site_id = ee()->config->item('site_id');
		$record->title = $title;
		$record->location_id = $locationModel->id;
		$record->is_image = $fileIsImage;
		$record->mime_type = ee()->mime_type->ofFile($filePath);
		$record->file_name = $fileName;
		$record->extension = isset($fileInfo['extension']) ? $fileInfo['extension'] : '';
		$record->file_size = filesize($filePath);
		$record->description = ee('Security/XSS')->clean($this->description);
		$record->uploaded_by_member_id = ee()->session->userdata('member_id');
		$record->upload_date = time();
		$record->modified_by_member_id = ee()->session->userdata('member_id');
		$record->modified_date = time();

		// Prepare thumb varibale
		$thumbPath = null;

		// If file is image set width and height and get thumbnail
		if ($fileIsImage) {
			// Get the image size
			$imageSize = getimagesize($filePath);

			// Set width and height
			$record->width = $imageSize[0];
			$record->height = $imageSize[1];

			// Get a basic image object for the thumbnail service
			$basicImage = new BasicImage();
			$basicImage->location = $filePath;
			$basicImage->filename = $fileName;
			$basicImage->width = $record->width;
			$basicImage->height = $record->height;

			$thumbnailService = new ThumbnailService($basicImage);

			// Get the resulting file from the service
			$thumbPath = $thumbnailService->get();
		}

		// Already validated so bypass
		$this->bypassUploadValidation = true;

		// Check if there is a thumbnail
		if ($thumbPath) {
			// Save original filePath and fileName
			$origFilePath = $this->filePath;
			$origFileName = $this->fileName;

			// Temporarily set new filePath and fileName
			$this->filePath = $thumbPath;
			$this->fileName = "_thumbs/{$fileName}";

			// Upload thumbnail
			$result = $this->uploadFile();

			// Reset filePath and fileName
			$this->filePath = $origFilePath;
			$this->fileName = $origFileName;

			// Check if thumbnail upload failed
			if ($result->hasErrors) {
				return $result;
			}
		}

		// Upload file
		$result = $this->uploadFile();

		// Check if file upload failed
		if ($result->hasErrors) {
			return $result;
		}

		// Reset bypass
		$this->bypassUploadValidation = false;

		// Save the record
		$record->save();

		// Clean up cache
		FileCacheService::cleanUp();

		// Get the ValidationResult model
		$validationResult = new ValidationResult();

		// Return the result
		return $validationResult;
	}

	/**
	 * Upload a file
	 */
	public function uploadFile()
	{
		// Run validation if the addFile method has not requested a bypass
		if (! $this->bypassUploadValidation) {
			// Validate upload
			$validationResult = $this->validateFileForUpload(
				$this->locationHandle,
				$this->filePath
			);

			// Check if validation failed
			if ($validationResult->hasErrors) {
				return $validationResult;
			}
		}

		// Get the Location Model
		$locationsService = LocationsService::getInstance();
		$locationModel = $locationsService->{$this->locationHandle};

		// Get the file handling service
		$fileHandling = '\\BuzzingPixel\\Treasury\\Service\\FileHandling\\';
		$fileHandling .= $locationModel->type;
		$fileHandling = new $fileHandling;

		// Send to the correct upload service for upload
		$result = $fileHandling->upload(
			$this->locationHandle,
			$this->filePath,
			$this->fileName
		);

		return $result;
	}

	/**
	 * Validate file upload data
	 */
	private function validateFileForUpload()
	{
		// Start errors array
		$errors = array();

		// Load the mime_type library
		ee()->load->library('mime_type');

		// Prepare safe variable
		$safe = null;

		// Get the Location Model
		$locationsService = LocationsService::getInstance();
		$locationModel = $locationsService->{$this->locationHandle};

		// If the appropriate items are present in our data, verify safety
		if ($locationModel->handle && file_exists($this->filePath)) {
			$safe = ee()->mime_type->fileIsSafeForUpload($this->filePath);

			// If location allows only images, verify upload is an image
			if (
				$locationModel->allowed_file_types === 'images_only' &&
				! ee()->mime_type->fileIsImage($this->filePath)
			) {
				$errors[] = lang('only_images_allowed');
			}
		}

		// If the $safe var is null, the file did not get uploaded
		if ($safe === null) {
			$errors[] = lang('problem_uploading_file');

		// Otherwise if the file is unsafe, throw an error
		} elseif (! $safe) {
			$errors[] = lang('file_not_allowed');
		}

		// Get the ValidationResult model
		$validationResult = new ValidationResult();

		// Set validation errors
		$validationResult->addErrors($errors);

		// Return the validation result
		return $validationResult;
	}
}
