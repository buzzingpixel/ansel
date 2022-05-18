<?php

namespace BuzzingPixel\Treasury\API;

use BuzzingPixel\Treasury\Factory\Record as RecordFactory;
use BuzzingPixel\Treasury\Model\ValidationResult;
use BuzzingPixel\Treasury\Service\LocationsService;
use BuzzingPixel\Treasury\Record\Locations as LocationsRecord;

class Locations extends BaseAPI
{
	/**
	 * Save Location
	 *
	 * @param array $saveData {
	 *     @var string $name Required
	 *     @var string $handle Required
	 *     @var string $type Required
	 *     @var array $settings {
	 *         @var string $access_key_id Required for S3 locations
	 *         @var string $secret_access_key Required for S3 locations
	 *         @var string $bucket Required for S3 locations
	 *         @var string $subfolder Optional for S3 locations
	 *         @var string $url Required
	 *         @var string $path Required for local locations
	 *         @var enum $allowed_file_types images_only|all_file_types
	 *     }
	 * }
	 * @param string $origHandle Use original handle to update existing
	 */
	public function saveLocation($saveData = array(), $origHandle = '')
	{
		// Validate upload
		$validationResult = self::validateSaveData($saveData, $origHandle);

		// Check if validation failed
		if ($validationResult->hasErrors) {
			return $validationResult;
		}

		// Get the Locations Record
		if ($origHandle) {
			$locationsRecordFactory = new RecordFactory('Locations');

			// Filter record and get result
			$locationRecord = $locationsRecordFactory
				->filter('site_id', ee()->config->item('site_id'))
				->filter('handle', $origHandle)
				->first();
		} else {
			$locationRecord = new LocationsRecord();
		}

		/**
		 * Set record properties
		 */

		$locationRecord->site_id = ee()->config->item('site_id');
		$locationRecord->name = ee('Security/XSS')->clean($saveData['name']);
		$locationRecord->handle = ee('Security/XSS')->clean($saveData['handle']);
		$locationRecord->type = ee('Security/XSS')->clean($saveData['type']);

		// Clean settings
		$settings = array();
		foreach ($saveData['settings'] as $key => $val) {
			$key = ee('Security/XSS')->clean($key);
			$val = ee('Security/XSS')->clean($val);
			$settings[$key] = $val;
		}

		// Set settings
		$locationRecord->settings = $settings;

		// Save record
		$locationRecord->save();

		// Return the result
		return new ValidationResult();
	}

	/**
	 * Get all locations
	 *
	 * @param string $order Specify as 'handle' or 'handle:asc' or 'name:desc'
	 *                      Defaults to name:asc
	 */
	public function getAllLocations($order = 'name')
	{
		// Explode the order into an array
		$order = explode(':', $order);

		// Set the order key
		$orderKey = $order[0];

		// Set the order sorting
		$sort = 'asc';

		// Check if sorting has been specified
		if (isset($order[1]) && $order[1] === 'desc') {
			$sort = 'desc';
		}

		// Get locations service instance
		$locationsService = LocationsService::getInstance();

		// Return locations collection
		return $locationsService->getAllLocationsCollection($orderKey, $sort);
	}

	/**
	 * Get location by handle
	 *
	 * @param string $handle
	 */
	public function getLocationByHandle($handle = '')
	{
		// Get locations service instance
		$locationsService = LocationsService::getInstance();

		// If a handle has not been specified, return null
		if (! $handle) {
			return null;
		}

		// Return location
		return $locationsService->{$handle};
	}

	/**
	 * Delete location by handle
	 *
	 * @param string $handle
	 */
	public function removeLocation($handle = '')
	{
		// Get the ValidationResult model
		$validationResult = new ValidationResult();

		// Get the Locations Record
		$locationsRecordFactory = new RecordFactory('Locations');

		// Filter record and get result
		$locationRecord = $locationsRecordFactory
			->filter('handle', $handle)
			->first();

		// Check if there was a result for this handle
		if (! $locationRecord->handle) {
			// Set the errors array
			$validationResult->addError(lang('location_not_found'));

			// Return the validation model
			return $validationResult;
		}

		// Get the files record factory
		$filesRecordFactory = new RecordFactory('Files');

		// Filter files by location and delete them
		$filesRecordFactory->filter('location_id', $locationRecord->id)
			->delete();

		// Delete the record
		$locationRecord->delete();

		// Return the result
		return $validationResult;
	}

	/**
	 * Validate save data
	 */
	private function validateSaveData($saveData, $origHandle = '')
	{
		// Start errors array
		$errors = array();

		// Set validation rules
		$rules = array(
			'name' => 'required',
			'handle' => 'required|alphaDash',
			'type' => 'enum[local, amazon_s3, sftp, ftp]',
			'allowed_file_types' => 'enum[images_only, all_file_types]'
		);

		// Set specific validation rules based on type selection
		if (isset($saveData['type'])) {
			if ($saveData['type'] === 'local') {
				$rules['url'] = 'required';
				$rules['path'] = 'required';
			} elseif ($saveData['type'] === 'amazon_s3') {
				$rules['access_key_id'] = 'required';
				$rules['secret_access_key'] = 'required';
				$rules['bucket'] = 'required';
			}
		}

		// Create a validator based on the rules
		$eeValidator = ee('Validation')->make($rules);

		// Add settings to the top level for validation purposes
		if (
			isset($saveData['settings']) &&
			gettype($saveData['settings']) === 'array'
		) {
			$saveData = array_merge($saveData, $saveData['settings']);
		}

		// Validate data
		$validation = $eeValidator->validate($saveData);

		// Start by assuming handle is unique
		$uniqueHandle = true;

		// Check that theory
		if (isset($saveData['handle'])) {
			// Get the Locations Record
			$locationsRecordFactory = new RecordFactory('Locations');

			// Check for any locations where this handle already exists and is
			// not this source
			$locationsRecordFactory->filter('handle', $saveData['handle']);

			if ($origHandle) {
				$locationsRecordFactory->filter('handle', '!=', $origHandle);
			}

			if ($locationsRecordFactory->count()) {
				$uniqueHandle = false;
			}
		}

		// Validate the the handle exists
		if ($origHandle) {
			// Try to get the specified location
			$locationsService = LocationsService::getInstance();
			$locationModel = $locationsService->{$origHandle};

			// If it does not exist, add an error to the errors array
			if (! $locationModel) {
				$errors[] = lang('location_not_found');
			}
		}

		// Check for validation errors
		if (! $validation->isValid()) {
			// Loop through validation errors
			foreach ($validation->getAllErrors() as $fieldName => $error) {
				$errors[] = lang($fieldName) . ': ' . implode(' ', $error);
			}
		}

		// Check if field handle is unique
		if (! $uniqueHandle) {
			$errors[] = lang('unique_handle_required');
		}

		// Get the ValidationResult model
		$validationResult = new ValidationResult();

		// Set validation errors
		$validationResult->addErrors($errors);

		// Return the validation result
		return $validationResult;
	}
}
