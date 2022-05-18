<?php

namespace BuzzingPixel\Treasury\Controller;

use BuzzingPixel\Treasury\Service\CP\Sidebar AS SidebarService;
use BuzzingPixel\Treasury\Service\CP\Assets as AssetsService;
use BuzzingPixel\Treasury\Service\LocationsService;

class Upload
{
	// ID
	private $locationId;

	// Location model
	private $locationModel;

	// Post data
	private $saveData;

	/**
	 * Constructor
	 *
	 * @param int|bool $locationId
	 */
	public function __construct($locationId = false)
	{
		// Set the ID
		$this->locationId = $locationId;

		// Get the Location Model
		$locationsService = LocationsService::getInstance();
		$this->locationModel = $locationsService->getLocationById($locationId);
	}

	/**
	 * Set post data
	 *
	 * @param array $data
	 * @param array $files
	 * @return self
	 */
	public function setPostData($data, $files)
	{
		if (gettype($data) === 'array' && gettype($files) === 'array') {
			$this->saveData = array();

			if (isset($data['title'])) {
				$this->saveData['title'] = $data['title'];
			}

			if (isset($data['description'])) {
				$this->saveData['description'] = $data['description'];
			}

			if (isset($files['file']['name'])) {
				$this->saveData['fileName'] = $files['file']['name'];
			}

			if (isset($files['file']['tmp_name'])) {
				$this->saveData['filePath'] = $files['file']['tmp_name'];
			}
		}

		return $this;
	}

	/**
	 * Show the upload dialog
	 */
	public function show()
	{
		// Check if this is a modal request
		$modal = ee()->input->get('modal') === 'true';

		// Set CP Assets
		AssetsService::set('editLocations');

		// Start view
		$view = array();

		// Set postUrl params
		$params = array();
		if ($modal) {
			$params['modal'] = 'true';
		}

		// Set an error if no location specified
		if (! $this->locationId) {
			// Set an alert
			ee('CP/Alert')->makeInline('error')
				->asIssue()
				->cannotClose()
				->withTitle(lang('no_upload_location_specified'))
				->addToBody(lang('no_upload_location_specified_body'))
				->now();
		}

		// Set heading
		if ($this->locationModel->name) {
			$heading = lang('upload_to') . ' ' . $this->locationModel->name;
		} else {
			$heading = lang('error');
		}

		// Render view
		$view['body'] = ee('View')->make('treasury:upload')->render(array(
			'postUrl' => "upload/{$this->locationId}",
			'params' => $params,
			'heading' => $heading,
			'locationId' => $this->locationId,
			'locationModel' => $this->locationModel
		));

		// Check if this is a modal request and exit with only the markup
		if ($modal) {
			exit($view['body']);
		}

		// Get the EE CP sidebar
		SidebarService::render();

		// Set breadcrumb
		$view['breadcrumb'] = array(
			ee('CP/URL')->make('addons/settings/treasury')->compile() => lang('treasury')
		);

		// Set heading
		$view['heading'] = $heading;

		// Return view
		return $view;
	}

	/**
	 * Process upload
	 */
	public function save()
	{
		// Check if modal
		$modal = ee()->input->get('modal') === 'true';

		// Run the upload
		$result = ee('treasury:UploadAPI')
			->locationHandle($this->locationModel->handle)
			->filePath($this->saveData['filePath'])
			->fileName($this->saveData['fileName'])
			->title($this->saveData['title'])
			->description($this->saveData['description'])
			->addFile();

		// Check if validation was successful
		if ($result->hasErrors) {
			// Count the errors and get the appropriate title lang key
			if (count($result->errors) > 1) {
				$errorTitle = lang('upload_errors');
			} else {
				$errorTitle = lang('upload_error');
			}

			// Concatenate the errors
			$errors = '<ul><li>' . implode('</li><li>', $result->errors) . '</li></ul>';

			// Set errors
			ee('CP/Alert')->makeInline('upload_errors')
				->asIssue()
				->canClose()
				->withTitle($errorTitle)
				->addToBody($errors)
				->defer();

			// Set redirect URL
			$redirectUrl = ee(
				'CP/URL',
				"addons/settings/treasury/upload/{$this->locationId}"
			);

			// Send error message if ajax response
			if ($modal) {
				$redirectUrl->setQueryStringVariable('modal', 'true');

				ee()->output->send_ajax_response(array(
					'hasErrors' => true,
					'loadUrl' => $redirectUrl->compile(),
					'loadType' => 'html'
				));

				return;
			}

			// Redirect to this page
			ee()->functions->redirect($redirectUrl);

			return;
		}

		// Send success message if ajax response
		if ($modal) {
			$fileModel = ee('treasury:FilesAPI')->getFirst();

			$redirectUrl = ee(
				'CP/URL',
				"addons/settings/treasury/fileJson/{$fileModel->id}"
			);

			$redirectUrl->setQueryStringVariable('modal', 'true');

			ee()->output->send_ajax_response(array(
				'hasErrors' => false,
				'loadUrl' => $redirectUrl->compile(),
				'loadType' => 'json'
			));

			return;
		}

		// Show the success message
		ee('CP/Alert')->makeInline('treasury_file_uploaded')
			->asSuccess()
			->canClose()
			->withTitle(lang('treasury_file_uploaded_title'))
			->addToBody(lang('treasury_file_uploaded_body'))
			->defer();

		// Redirect to this page
		ee()->functions->redirect(
			ee(
				'CP/URL',
				"addons/settings/treasury/showLocation/{$this->locationId}"
			)
		);
	}
}
