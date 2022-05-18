<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

use BuzzingPixel\Treasury\Controller\Locations;
use BuzzingPixel\Treasury\Controller\Settings;
use BuzzingPixel\Treasury\Controller\Updates;
use BuzzingPixel\Treasury\Controller\EditLocations;
use BuzzingPixel\Treasury\Controller\Upload;
use BuzzingPixel\Treasury\Controller\Files;
use BuzzingPixel\Treasury\Service\License;
use BuzzingPixel\Treasury\Service\Settings as SettingsService;

class Treasury_mcp
{
	/**
	 * Constructor
	 */
	public function __construct()
	{
		// Run license key check
		License::check();
	}

	/**
	 * Increment phone home
	 */
	public function incrementPhoneHome()
	{
		// Get settings
		$settings = SettingsService::getInstance();
		$settings->phone_home = strtotime('+1 day', time());
		$settings->save();
		exit();
	}

	/**
	 * Locations
	 */
	public function index()
	{
		return $this->showLocation();
	}

	/**
	 * Show location
	 *
	 * @param int $locationId
	 */
	public function showLocation($locationId = false)
	{
		// Get controller instance
		$controller = new Locations($locationId);

		// Check if there is post data
		if ($_POST) {
			$controller->setPostData($_POST);
		}

		// Return the controller show method
		return $controller->show();
	}

	/**
	 * Show settings
	 */
	public function settings()
	{
		// Get controller instance
		$controller = new Settings();

		// If there is post data, process it
		if ($_POST) {
			$controller->setPostData($_POST)->save();
		}

		// Return the controller show method
		return $controller->show();
	}

	/**
	 * Show updates
	 */
	public function updates()
	{
		// Get controller instance
		$controller = new Updates();

		// Return the controller show method
		return $controller->show();
	}

	/**
	 * Upload a file
	 *
	 * @param int $locationId
	 */
	public function upload($locationId = false)
	{
		// Get controller instance
		$controller = new Upload($locationId);

		// If there is post data, process it
		if ($_POST) {
			$controller->setPostData($_POST, $_FILES)->save();
		}

		// Return the controller show method
		return $controller->show();
	}

	/**
	 * Edit location
	 *
	 * @param int $locationId
	 */
	public function editLocation($locationId = false)
	{
		// Get controller instance
		$controller = new EditLocations($locationId);

		// If there is post data, process it
		if ($_POST) {
			$controller->setPostData($_POST)->save();
		}

		// Return the controller show method
		return $controller->show();
	}

	/**
	 * Remove location
	 */
	public function removeLocation()
	{
		// Set an initial location ID of zero
		$locationId = 0;

		// If there is an id in the post data, set it
		if (isset($_POST['id'])) {
			$locationId = (int) $_POST['id'];
		}

		// Get controller instance
		$controller = new EditLocations($locationId);

		// Run the controller remove method
		$controller->remove();
	}

	/**
	 * Edit File
	 *
	 * @param int $fileId
	 */
	public function editFile($fileId = false)
	{
		// Get controller instance
		$controller = new Files($fileId);

		// If there is post data, process it
		if ($_POST) {
			$controller->setPostData($_POST)->save();
		}

		// Return the controller show method
		return $controller->show();
	}

	/**
	 * Edit File
	 *
	 * @param int $fileId
	 */
	public function deleteFiles($fileId = false)
	{
		// Get controller instance
		$controller = new Files($fileId);

		// If there is post data, process it
		$controller->setPostData($_POST)->delete();
	}

	/**
	 * File json
	 *
	 * @param int $fileId
	 */
	public function fileJson($fileId = false)
	{
		// Get controller instance
		$controller = new Files($fileId);

		// Return the controller json method
		$controller->fileJsonResponse();
	}
}
