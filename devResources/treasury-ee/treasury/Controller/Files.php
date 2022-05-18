<?php

namespace BuzzingPixel\Treasury\Controller;

use BuzzingPixel\Treasury\Service\CP\Sidebar AS SidebarService;
use BuzzingPixel\Treasury\Service\CP\Assets as AssetsService;

class Files
{
	// ID
	private $fileId;

	// File model
	private $fileModel;

	// Post data
	private $saveData;

	/**
	 * Constructor
	 *
	 * @param int $fileId
	 */
	public function __construct($fileId = false)
	{
		// If there is not a file ID, there is nothing to do and we'll show an
		// error in the show method
		if (! $fileId) {
			return;
		}

		// Set the ID
		$this->fileId = $fileId;

		// Get the file model
		$this->fileModel = ee('treasury:FilesAPI')
			->filter('id', $fileId)
			->getFirst();
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
			$this->saveData = array();

			if (isset($data['title'])) {
				$this->saveData['title'] = $data['title'];
			}

			if (isset($data['description'])) {
				$this->saveData['description'] = $data['description'];
			}

			if (isset($data['return'])) {
				$this->saveData['return'] = $data['return'];
			}

			if (
				isset($data['selection']) &&
				gettype($data['selection']) === 'array'
			) {
				$this->saveData['selection'] = array();

				foreach ($data['selection'] as $id) {
					$this->saveData['selection'][] = (int) $id;
				}
			}
		}

		return $this;
	}

	/**
	 * Show the file editor
	 */
	public function show()
	{
		// Set an error if no file specified
		if (! $this->fileModel) {
			// Set an alert
			ee('CP/Alert')->makeInline('error')
				->asIssue()
				->cannotClose()
				->withTitle(lang('no_file_specified'))
				->addToBody(lang('no_file_specified_body'))
				->now();
		}

		// Set CP Assets
		AssetsService::set();

		// Get the EE CP sidebar
		SidebarService::render();

		// Start view
		$view = array();

		// Set breadcrumb
		$view['breadcrumb'] = array(
			ee('CP/URL')->make('addons/settings/treasury')->compile() => lang('treasury')
		);

		// Set heading
		if ($this->fileModel) {
			$view['heading'] = lang('edit_file');
		} else {
			$view['heading'] = lang('error');
		}

		// Render view
		$view['body'] = ee('View')->make('treasury:editFile')->render(array(
			'postUrl' => "editFile/{$this->fileId}",
			'heading' => $view['heading'],
			'fileId' => $this->fileId,
			'fileModel' => $this->fileModel
		));

		return $view;
	}

	/**
	 * File json response
	 */
	public function fileJsonResponse()
	{
		// Check if ther is a model
		if (! $this->fileModel) {
			ee()->output->send_ajax_response(array(
				'file' => false
			));
		}

		// Get the model as an array
		$fileArray = $this->fileModel->asArray();

		// Add various keys to match EE js object
		$fileArray['credit'] = '';
		$fileArray['file_hw_original'] = "{$this->fileModel->height} {$this->fileModel->width}";
		$fileArray['file_id'] = $this->fileModel->id;
		$fileArray['isImage'] = $this->fileModel->is_image;
		$fileArray['path'] = $fileArray['file_url'] = $this->fileModel->file_url;
		$fileArray['thumb_path'] = $fileArray['thumb_url'] = $this->fileModel->thumb_url;
		$fileArray['location'] = '';
		$fileArray['upload_location_id'] = $this->fileModel->location_id;

		// Send json response
		ee()->output->send_ajax_response($fileArray);
	}

	/**
	 * Process save
	 */
	public function save()
	{
		// Run the save method
		$result = ee('treasury:FilesAPI')->updateFile(
			$this->fileId,
			$this->saveData['title'],
			$this->saveData['description']
		);

		// Check if validation was successful
		if ($result->hasErrors) {
			// Count the errors and get the appropriate title lang key
			if (count($result->errors) > 1) {
				$errorTitle = lang('file_edit_errors');
			} else {
				$errorTitle = lang('file_edit_error');
			}

			// Concatenate the errors
			$errors = '<ul><li>' . implode('</li><li>', $result->errors) . '</li></ul>';

			// Set errors
			ee('CP/Alert')->makeInline('file_edit_errors')
				->asIssue()
				->canClose()
				->withTitle($errorTitle)
				->addToBody($errors)
				->defer();

			// Redirect to this page
			ee()->functions->redirect(
				ee(
					'CP/URL',
					"addons/settings/treasury/editFile/{$this->fileId}"
				)
			);

			return;
		}

		// Show the success message
		ee('CP/Alert')->makeInline('file_edited_successfully')
			->asSuccess()
			->canClose()
			->withTitle(lang('file_saved_title'))
			->addToBody(lang('file_saved_body'))
			->defer();

		// Redirect to this page
		ee()->functions->redirect(
			ee(
				'CP/URL',
				"addons/settings/treasury/showLocation/{$this->fileModel->location_id}"
			)
		);
	}

	/**
	 * Process delete
	 */
	public function delete()
	{
		// Make sure IDs is set
		$ids = isset($this->saveData['selection']) ?
			$this->saveData['selection'] :
			array();

		// Run the delete method
		ee('treasury:FilesAPI')->deleteFilesById($ids);

		// Show the success message
		ee('CP/Alert')->makeInline('files_deleted_successfully')
			->asSuccess()
			->canClose()
			->withTitle(lang('files_deleted_title'))
			->addToBody(lang('files_deleted_body'))
			->defer();

		// Make sure return is set
		$return = isset($this->saveData['return']) ?
			$this->saveData['return'] :
			'addons/settings/treasury';

		// Redirect to the return page
		ee()->functions->redirect(ee('CP/URL', $return));
	}
}
