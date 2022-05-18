<?php

namespace BuzzingPixel\Treasury\API;

use BuzzingPixel\Treasury\Factory\Record as RecordFactory;
use BuzzingPixel\Treasury\Factory\Model as ModelFactory;
use BuzzingPixel\Treasury\Model\ValidationResult;
use BuzzingPixel\Treasury\Service\LocationsService;

class Files extends BaseAPI
{
	private $filesRecordFactory;
	private $siteIdSet = false;
	private $orderingSet = false;

	public function __construct()
	{
		// Get the files record factory
		$this->filesRecordFactory = new RecordFactory('Files');
	}

	/**
	 * Apply filtering defaults
	 */
	private function applyDefaultFiltering()
	{
		if (! $this->siteIdSet) {
			$this->filesRecordFactory->filter(
				'site_id',
				ee()->config->item('site_id')
			);
		}

		if (! $this->orderingSet) {
			$this->filesRecordFactory->order('upload_date', 'desc');
		}
	}

	/**
	 * __call
	 *
	 * @return self
	 */
	public function __call($name, $args)
	{
		if ($name === 'filter') {
			if (isset($args[0])) {
				if ($args[0] === 'site_id') {
					$this->siteIdSet = true;
				} elseif ($args[0] === 'order') {
					$this->orderingSet = true;
				}
			}

			call_user_func_array(
				array($this->filesRecordFactory, $name),
				$args
			);
		} elseif ($name === 'limit' && isset($args[0])) {
			$this->filesRecordFactory->limit($args[0]);
		} elseif ($name === 'offset' && isset($args[0])) {
			$this->filesRecordFactory->offset($args[0]);
		} elseif ($name === 'order' && isset($args[0])) {
			$sort = isset($args[1]) ? $args[1] : 'desc';
			$this->filesRecordFactory->order($args[0], $sort);
		} elseif ($name === 'search' && isset($args[0])) {
			$this->filesRecordFactory->search('title', $args[0], 'or');
			$this->filesRecordFactory->search('mime_type', $args[0], 'or');
			$this->filesRecordFactory->search('file_name', $args[0], 'or');
			$this->filesRecordFactory->search('description', $args[0], 'or');
		}

		return $this;
	}

	/**
	 * Get files count matching criteria
	 */
	public function getCount()
	{
		// Apply default filtering
		$this->applyDefaultFiltering();

		// Get count
		return $this->filesRecordFactory->count();
	}

	/**
	 * Get first file result
	 *
	 * @param bool $emptyModelReturn
	 */
	public function getFirst($emptyModelReturn = false)
	{
		// Apply default filtering
		$this->applyDefaultFiltering();

		// Get record
		$record = $this->filesRecordFactory->first();

		// If the record is empty and no model return is requested, return null
		if (! $record->id && ! $emptyModelReturn) {
			return null;
		}

		// Get the model factory
		$filesModelFactory = new ModelFactory('Files');

		// Return model
		return $filesModelFactory->populateModel($record->asArray());
	}


	/**
	 * Get files
	 */
	public function getFiles()
	{
		// Apply default filtering
		$this->applyDefaultFiltering();

		// Get the record collection
		$recordCollection = $this->filesRecordFactory->all();

		// Get the model factory
		$filesModelFactory = new ModelFactory('Files');

		// Return the files collection
		return $filesModelFactory->collection(
			$recordCollection->asArray()
		);
	}

	/**
	 * Edit file
	 *
	 * @param int $fileId
	 * @param string $title
	 * @param string $description
	 */
	public function updateFile($fileId, $title, $description = '')
	{
		// Get the ValidationResult model
		$validationResult = new ValidationResult();

		// Start an errors array
		$errors = array();

		// Get the file record
		$fileRecordFactory = new RecordFactory('Files');

		// Filter record and get result
		$fileRecord = $fileRecordFactory
			->filter('id', $fileId)
			->first();

		// Check that the record returned a result
		if (! $fileRecord->id) {
			// Set validation errors
			$validationResult->addError(lang('could_not_find_file'));

			// Return the validation result
			return $validationResult;
		}

		// Clean inputs
		$title = ee('Security/XSS')->clean($title);
		$description = ee('Security/XSS')->clean($description);

		// Check that we have a title
		if (! $title) {
			// Set validation errors
			$validationResult->addError(lang('file_title_required'));

			// Return the validation result
			return $validationResult;
		}

		// Update record properties
		$fileRecord->title = $title;
		$fileRecord->description = $description;
		$fileRecord->modified_by_member_id = ee()->session->userdata('member_id');
		$fileRecord->modified_date = time();

		// Save the record
		$fileRecord->save();

		// Return the result
		return $validationResult;
	}

	/**
	 * Delete files
	 *
	 * @param array $ids Array of file id integers
	 */
	public function deleteFilesById($ids = array())
	{
		$ids = gettype($ids === 'array') ? $ids : array();

		// Get the ValidationResult model
		$validationResult = new ValidationResult();

		// Get the file models
		$this->siteIdSet = true;
		$this->filesRecordFactory->filter('id', 'IN', $ids);
		$files = $this->getFiles();

		// Loop through the file models
		foreach ($files as $file) {
			// Get the file handling service
			$fileHandling = '\\BuzzingPixel\\Treasury\\Service\\FileHandling\\';
			$fileHandling .= $file->location->type;
			$fileHandling = new $fileHandling;

			// Delete the thumbnail
			$fileHandling->deleteFile(
				$file->location->handle,
				"_thumbs/{$file->file_name}"
			);

			// Delete the file
			$fileHandling->deleteFile(
				$file->location->handle,
				$file->file_name
			);
		}

		// Get the file record factory
		$fileRecordFactory = new RecordFactory('Files');

		// Filter record and delete
		$fileRecordCollection = $fileRecordFactory
			->filter('id', 'IN', $ids)
			->delete();

		// Return the result
		return $validationResult;
	}

	/**
	 * Delete files
	 *
	 * @param string $locationHandle
	 * @param string $path
	 */
	public function deleteFileByPath($locationHandle, $path)
	{
		// Get the ValidationResult model
		$validationResult = new ValidationResult();

		// Get the Location Model
		$locationsService = LocationsService::getInstance();
		$location = $locationsService->{$locationHandle};

		// Get the file handling service
		$fileHandling = '\\BuzzingPixel\\Treasury\\Service\\FileHandling\\';
		$fileHandling .= $location->type;
		$fileHandling = new $fileHandling;

		// Delete the file
		$fileHandling->deleteFile($locationHandle, $path);

		// Return the result
		return $validationResult;
	}

	/**
	 * File exists
	 *
	 * @param string $locationHandle
	 * @param string $fileName
	 */
	public function fileExists($locationHandle, $fileName)
	{
		// Get the Location Model
		$locationsService = LocationsService::getInstance();
		$locationModel = $locationsService->{$locationHandle};

		// Get the file handling service
		$fileHandling = '\\BuzzingPixel\\Treasury\\Service\\FileHandling\\';
		$fileHandling .= $locationModel->type;
		$fileHandling = new $fileHandling;

		return $fileHandling->fileExists($locationHandle, $fileName);
	}

	/**
	 * Cache file
	 *
	 * @param int $fileId
	 * @return string
	 */
	public function cacheFile($fileId)
	{
		// Get the file model
		$fileModel = ee('treasury:FilesAPI')
			->filter('id', $fileId)
			->getFirst();

		// Get the file handling service
		$fileHandling = '\\BuzzingPixel\\Treasury\\Service\\FileHandling\\';
		$fileHandling .= $fileModel->location->type;
		$fileHandling = new $fileHandling;

		return $fileHandling->getFileAndCache($fileModel);
	}
}
