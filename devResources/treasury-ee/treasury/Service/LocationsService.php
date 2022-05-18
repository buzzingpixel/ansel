<?php

namespace BuzzingPixel\Treasury\Service;

use BuzzingPixel\Treasury\Pattern\Singleton;
use BuzzingPixel\Treasury\Factory\Record as RecordFactory;
use BuzzingPixel\Treasury\Factory\Model as ModelFactory;
use BuzzingPixel\Treasury\Service\Config;

class LocationsService extends Singleton
{
	/**
	 * @var Singleton The reference to *Singleton* instance of this class
	 */
	protected static $instance;

	/**
	 * @var locations
	 */
	private $locations = array();

	/**
	 * @var id map
	 */
	private $idMap = array();

	/**
	 * @var all locations collection
	 */
	private $allrecordsCollection;

	/**
	 * Get magic method
	 *
	 * @param string $name
	 */
	public function __get($name)
	{
		return $this->getLocationByHandle($name);
	}

	/**
	 * Get location by handle
	 *
	 * @param string $handle
	 * @param bool $returnNewModelOnNoResults
	 */
	public function getLocationByHandle(
		$handle,
		$returnNewModelOnNoResults = false
	)
	{
		// Check if the location has already been retrieved
		if (isset($this->locations[$handle])) {
			return $this->locations[$handle];
		}

		// Get the Locations Record
		$locationsRecordFactory = new RecordFactory('Locations');

		// Filter record and get result
		$locationRecord = $locationsRecordFactory
			->filter('site_id', ee()->config->item('site_id'))
			->filter('handle', $handle)
			->first();

		// Get the Locations Model
		$locationsModelFactory = new ModelFactory('Locations');

		// Populate the model with record data
		$locationModel = $locationsModelFactory->populateModel(
			$locationRecord->getFlatArray()
		);

		// Check if the model has data
		if ($locationModel->handle) {
			$locationModel = $this->checkLocationFileConfig($locationModel);
			$this->locations[$locationModel->handle] = $locationModel;
			$this->idMap[$locationModel->id] = $locationModel->handle;
			return $locationModel;
		}

		// If we should return a new model when no results
		if ($returnNewModelOnNoResults) {
			return $locationModel;
		}

		// Otherwise return null
		return null;
	}

	/**
	 * Get location by ID
	 *
	 * @param int $id
	 * @param bool $returnNewModelOnNoResults
	 */
	public function getLocationById($id, $returnNewModelOnNoResults = false)
	{
		// Check if the location has already been retrieved
		if (
			isset($this->idMap[$id]) &&
			isset($this->locations[$this->idMap[$id]])
		) {
			return $this->locations[$this->idMap[$id]];
		}

		// Get the Locations Record
		$locationsRecordFactory = new RecordFactory('Locations');

		// Filter record and get result
		$locationRecord = $locationsRecordFactory
			->filter('site_id', ee()->config->item('site_id'))
			->filter('id', $id)
			->first();

		// Get the Locations Model
		$locationsModelFactory = new ModelFactory('Locations');

		// Populate the model with record data
		$locationModel = $locationsModelFactory->populateModel(
			$locationRecord->getFlatArray()
		);

		// Check if the model has data
		if ($locationModel->handle) {
			$locationModel = $this->checkLocationFileConfig($locationModel);
			$this->locations[$locationModel->handle] = $locationModel;
			$this->idMap[$locationModel->id] = $locationModel->handle;
			return $locationModel;
		}

		// If we should return a new model when no results
		if ($returnNewModelOnNoResults) {
			return $locationModel;
		}

		// Otherwise return null
		return null;
	}

	/**
	 * Get all locations
	 *
	 * @param string $order
	 * @param string $sort
	 */
	public function getAllLocationsCollection($order = 'name', $sort = 'asc')
	{
		if ($this->allrecordsCollection) {
			return $this->allrecordsCollection;
		}

		// Get the Locations Record
		$locationsRecordFactory = new RecordFactory('Locations');

		// Filter record and get result
		$recordsCollection = $locationsRecordFactory
			->filter('site_id', ee()->config->item('site_id'))
			->order($order, $sort)
			->all();

		// Set up array for data
		$data = array();

		// Loop through the locations
		foreach ($recordsCollection as $location) {
			$data[] = $location->getFlatArray();
		}

		// Get the locations model
		$locationsModelFactory = new ModelFactory('Locations');

		// Populate the locations model
		$modelCollection = $locationsModelFactory->collection($data);

		// Set cache
		foreach ($modelCollection as $model) {
			$model = $this->checkLocationFileConfig($model);
			$this->locations[$model->handle] = $model;
			$this->idMap[$model->id] = $model->handle;
		}

		// Set the collection cache
		$this->allrecordsCollection = $modelCollection;

		// Return the model collection
		return $modelCollection;
	}

	/**
	 * Check location file config
	 *
	 * @param object $locationModel
	 */
	private function checkLocationFileConfig($locationModel)
	{
		// Loop through the location model
		foreach ($locationModel as $key => $val) {
			// We don't want to affect id, site_id, or handle
			if ($key === 'id' || $key === 'site_id' || $key === 'handle') {
				continue;
			}

			// Get config value
			$configVal = Config::get(
				'locations',
				$locationModel->handle,
				$key
			);

			// Check if the config value is set
			if ($configVal !== null) {
				$locationModel->{$key} = $configVal;
			}
		}

		return $locationModel;
	}
}
