<?php

namespace BuzzingPixel\Treasury\Controller\FileField;

use BuzzingPixel\Treasury\Factory\Model;

class Settings
{
	private $settings;

	/**
	 * FieldSettings constructor
	 *
	 * @param array $data
	 */
	public function __construct($data = array())
	{
		// Get settings model factory
		$modelFactory = new Model('FileFieldSettings');

		// Get a blank model
		$blank = $modelFactory->populateModel(array());

		// Loop through the properties of the blank model
		foreach ($blank as $key => $val) {
			// Make sure property is set
			if (isset($data[$key])) {
				// Clean the data
				$data[$key] = ee('Security/XSS')->clean($data[$key]);
			}
		}

		// Populate the settings model
		$this->settings = $modelFactory->populateModel($data);
	}

	/**
	 * Render field settings
	 */
	public function render()
	{
		// Create an array for the choices
		$choices = array(
			'' => '--'
		);

		// Loop through locations
		foreach (ee('treasury:LocationsAPI')->getAllLocations() as $location) {
			// Populate the choices array
			$choices[$location->handle] = $location->name;
		}

		// Return fields for ExpressionEngine
		return array(
			array(
				'title' => 'storage_location',
				'desc' => 'storage_location_description',
				'fields' => array(
					'location' => array(
						'type' => 'select',
						'choices' => $choices,
						'value' => $this->settings->location
					)
				)
			),
			array(
				'title' => 'images_only',
				'fields' => array(
					'images_only' => array(
						'type' => 'yes_no',
						'value' => $this->settings->images_only
					)
				)
			)
		);
	}

	/**
	 * Render Low Vars settings
	 */
	public function renderLowVars()
	{
		// Create an array for the choices
		$choices = array(
			'' => '--'
		);

		// Loop through locations
		foreach (ee('treasury:LocationsAPI')->getAllLocations() as $location) {
			// Populate the choices array
			$choices[$location->handle] = $location->name;
		}

		// Set lang variables
		$storage_location = lang('storage_location');
		$storage_location_description = lang('storage_location_description');

		// Set images_only chosen status
		$imagesOnlyYesChosen = $this->settings->images_only ? 'chosen' : '';
		$imagesOnlyNoChosen = $this->settings->images_only ? '' : 'chosen';

		// Get lang
		$yes = lang('yes');
		$no = lang('no');

		// Return fields for Low Variables
		return array(
			array(
				"{$storage_location}<em>{$storage_location_description}</em>",
				form_dropdown(
					'variable_settings[treasury_file][location]',
					$choices,
					$this->settings->location
				)
			),
			array(
				lang('images_only'),
				"<label class=\"choice mr {$imagesOnlyYesChosen} yes\">" . form_radio(array(
					'name' => 'variable_settings[treasury_file][images_only]',
					'value' => 'y',
					'checked' => $this->settings->images_only
				)) . " {$yes}</label>" .
				"<label class=\"choice {$imagesOnlyNoChosen} no\">" . form_radio(array(
					'name' => 'variable_settings[treasury_file][images_only]',
					'value' => 'n',
					'checked' => ! $this->settings->images_only
				)) . " {$no}</label>"
			)
		);
	}

	/**
	 * Validate post
	 */
	public function validate()
	{
		// Set up enums
		$enums = array();

		// Loop through locations
		foreach (ee('treasury:LocationsAPI')->getAllLocations() as $location) {
			$enums[] = $location->handle;
		}

		// Glue enums together
		$enums = implode(', ', $enums);

		// Set up the validator
		$validator = ee('Validation')->make(array(
			'location' => "required|enum[{$enums}]",
		));

		return $validator->validate($this->settings->asArray());
	}

	/**
	 * Save settings
	 */
	public function save()
	{
		// Get settings as array
		$settingsArray = $this->settings->asArray();

		// Unset some things
		unset($settingsArray['field_name']);
		unset($settingsArray['is_grid']);

		// Return settings
		return $settingsArray;
	}
}
