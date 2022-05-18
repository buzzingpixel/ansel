<?php

namespace BuzzingPixel\Treasury\Controller\FileField;

use BuzzingPixel\Treasury\Factory\Model;
use BuzzingPixel\Treasury\Service\CP\Assets as AssetsService;

class Field
{
	private $settings;
	private $fileModel;
	private $fileIdIsSet = false;

	/**
	 * Constructor
	 *
	 * @param array $settings
	 * @param int $fileId
	 */
	public function __construct($settings = array(), $fileId = 0)
	{
		// Get settings model factory
		$modelFactory = new Model('FileFieldSettings');

		// Get a blank model
		$blank = $modelFactory->populateModel(array());

		// Loop through the properties of the blank model
		foreach ($blank as $key => $val) {
			// Make sure property is set
			if (isset($settings[$key])) {
				// Clean the data
				$settings[$key] = ee('Security/XSS')->clean($settings[$key]);
			}
		}

		// Populate the settings model
		$this->settings = $modelFactory->populateModel($settings);

		// Get file model
		$this->fileModel = ee('treasury:FilesAPI')->filter('id', $fileId ?: 0)
			->getFirst(true);

		// Set if there is a file ID
		if ($fileId) {
			$this->fileIdIsSet = true;
		}
	}

	/**
	 * Render
	 */
	public function render()
	{
		// Set CP Assets
		AssetsService::set('fileField');

		// Set classes for button
		$classes = 'btn action js-treasury-file-field-add';

		// Check if the there is a file
		if ($this->fileModel->id) {
			$classes .= ' js-hide';
		}

		// Get the file picker link
		$filePickerLink = ee('treasury:FilePicker')
			->make($this->settings->location);

		// Check if we should do images only
		if ($this->settings->images_only) {
			$filePickerLink->imagesOnly();
		}

		// Finish file picker
		$filePickerLink = $filePickerLink->getLink(lang('add_file'))
			->setAttribute('class', $classes);

		// Low Variables has no validation of settings so if a location has not
		// been set, we need to display an error
		if (! $this->settings->location) {
			return lang('must_select_location');
		}

		// Return the view
		return ee('View')->make('treasury:fileField')->render(array(
			'filePickerLink' => $filePickerLink,
			'settings' => $this->settings,
			'fileModel' => $this->fileModel
		));
	}

	/**
	 * Validate
	 */
	public function validate()
	{
		// If a file ID has been set but there is no file model
		// that means the ID is invalid
		if ($this->fileIdIsSet && ! $this->fileModel->id) {
			return lang('file_not_found');
		}

		// If there is no file we don't need to validate anything
		if (! $this->fileModel->file_name) {
			return true;
		}

		// Check if images_only is set and not image
		if ($this->settings->images_only && ! $this->fileModel->is_image) {
			return lang('field_images_only');
		}

		// Otherwise return true
		return true;
	}
}
