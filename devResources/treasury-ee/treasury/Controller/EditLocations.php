<?php

namespace BuzzingPixel\Treasury\Controller;

use BuzzingPixel\Treasury\Service\CP\Sidebar AS SidebarService;
use BuzzingPixel\Treasury\Service\CP\Assets as AssetsService;
use BuzzingPixel\Treasury\Service\LocationsService;
use BuzzingPixel\Treasury\Service\Config;

class EditLocations
{
	// ID
	private $locationId;

	// Record
	private $record;

	// Model
	private $locationModel;

	// Save data
	private $saveData;

	/**
	 * Constructor
	 *
	 * @param int $locationId
	 */
	public function __construct($locationId = 0)
	{
		// Set the ID
		$this->locationId = $locationId;

		// Get the Location Model
		$locationsService = LocationsService::getInstance();
		$this->locationModel = $locationsService->getLocationById(
			$locationId,
			true
		);
	}

	/**
	 * Set post data
	 *
	 * @param array $data
	 * @return self
	 */
	public function setPostData($data)
	{
		if (gettype($data) === 'array') {
			$this->saveData = $data;
		}

		return $this;
	}

	/**
	 * Show edit form
	 */
	public function show()
	{
		// Set CP Assets
		AssetsService::set('editLocations');

		// Get the EE CP sidebar
		SidebarService::render();

		$view = array(
			'breadcrumb' => array(
				ee('CP/URL')->make('addons/settings/treasury')->compile() => lang('treasury')
			),
			'heading' => $this->locationModel->name ? lang('edit_location') : lang('add_location')
		);

		// Check if the user is authorized to be here
		if ((int) ee()->session->userdata('group_id') !== 1) {
			// Set an alert
			ee('CP/Alert')->makeInline('not_authorized')
				->asIssue()
				->cannotClose()
				->withTitle(lang('not_authorized'))
				->addToBody(lang('not_authorized_body'))
				->now();

			// Set view body
			$view['body'] = ee('View')->make('treasury:unauthorized')->render();

			// Return the rendered view
			return $view;
		}

		// Add body to view
		$view['body'] = ee('View')->make('treasury:editLocations')->render(
			array(
				'postUrl' => $this->locationId ? "editLocation/{$this->locationId}" : 'editLocation',
				'model' => $this->locationModel,
				'configPrivateKeyPath' => Config::get('private_key_path')
			)
		);

		// Return rendered view
		return $view;
	}

	/**
	 * Process location edits
	 */
	public function save()
	{
		// Save the location
		$result = ee('treasury:LocationsAPI')->saveLocation(
			$this->saveData,
			$this->locationModel->handle
		);

		// Check if validation was successful
		if ($result->hasErrors) {
			// Count the errors and get the appropriate title lang key
			if (count($result->errors) > 1) {
				$errorTitle = lang('location_edit_errors');
			} else {
				$errorTitle = lang('location_edit_error');
			}

			// Concatenate the errors
			$errors = '<ul><li>' . implode('</li><li>', $result->errors) . '</li></ul>';

			// Set errors
			ee('CP/Alert')->makeInline('location_errors')
				->asIssue()
				->canClose()
				->withTitle($errorTitle)
				->addToBody($errors)
				->defer();

			// Set redirect url
			$redirectUrl = 'addons/settings/treasury/editLocation';
			if ($this->locationId) {
				$redirectUrl .= "/{$this->locationId}";
			}

			// Redirect to this page
			ee()->functions->redirect(
				ee('CP/URL', $redirectUrl)
			);

			return;
		}

		// Show the success message
		ee('CP/Alert')->makeInline('treasury_location_saved')
			->asSuccess()
			->canClose()
			->withTitle(lang('treasury_location_saved_title'))
			->addToBody(lang('treasury_location_saved_body'))
			->defer();

		// Redirect to this page
		ee()->functions->redirect(
			ee('CP/URL', 'addons/settings/treasury')
		);
	}

	/**
	 * Remove location
	 */
	public function remove()
	{
		// Save the location
		$result = ee('treasury:LocationsAPI')->removeLocation(
			$this->locationModel->handle
		);

		// Check if validation was successful
		if ($result->hasErrors) {
			// Count the errors and get the appropriate title lang key
			if (count($result->errors) > 1) {
				$errorTitle = lang('location_remove_errors');
			} else {
				$errorTitle = lang('location_remove_error');
			}

			// Concatenate the errors
			$errors = '<ul><li>' . implode('</li><li>', $result->errors) . '</li></ul>';

			// Set errors
			ee('CP/Alert')->makeInline('location_remove_errors')
				->asIssue()
				->canClose()
				->withTitle($errorTitle)
				->addToBody($errors)
				->defer();

			// Set redirect url
			$redirectUrl = 'addons/settings/treasury';

			// Redirect to this page
			ee()->functions->redirect(
				ee('CP/URL', $redirectUrl)
			);

			return;
		}

		// Show the success message
		ee('CP/Alert')->makeInline('treasury_location_removed')
			->asSuccess()
			->canClose()
			->withTitle(lang('treasury_location_removed_title'))
			->addToBody(lang('treasury_location_removed_body'))
			->defer();

		// Redirect to this page
		ee()->functions->redirect(
			ee('CP/URL', 'addons/settings/treasury')
		);
	}
}
