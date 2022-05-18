<?php

namespace BuzzingPixel\Treasury\Controller;

use BuzzingPixel\Treasury\Service\CP\Sidebar AS SidebarService;
use BuzzingPixel\Treasury\Service\CP\Assets as AssetsService;
use BuzzingPixel\Treasury\Service\LocationsService;
use ExpressionEngine\Library\CP\Pagination;

class Locations
{
	// ID
	private $locationId;

	// Location model
	private $locationModel;

	// Search
	private $search;

	/**
	 * Constructor
	 *
	 * @param int|bool $locationId
	 */
	public function __construct($locationId = false)
	{
		// Set the ID
		$this->locationId = $locationId;

		if (! $locationId) {
			return;
		}

		// Get the Location Model
		$locationsService = LocationsService::getInstance();
		$this->locationModel = $locationsService->getLocationById($locationId);
	}

	/**
	 * Set post data
	 *
	 * @param array $data
	 * @return self
	 */
	public function setPostData($data)
	{
		if (isset($data['search']) && gettype($data['search']) === 'string') {
			$this->search = ee('Security/XSS')->clean($data['search']);
		}
	}

	/**
	 * Show locations
	 */
	public function show()
	{
		// Check if this is a modal request
		$modal = ee()->input->get('modal') === 'true';

		// Set CP Assets
		AssetsService::set('locations');

		// Get the EE CP sidebar
		SidebarService::render();

		// Get the files API
		$filesAPI = ee('treasury:FilesAPI');

		// Start view
		$view = array();

		// Set a variable for locations model
		$locationsCollection = false;

		// If there is no id, get all the locations for upload menu
		if (! $this->locationId) {
			$locationsService = LocationsService::getInstance();
			$locationsCollection = $locationsService->getAllLocationsCollection();
		}

		// If there is location ID, set the breadcrumb and heading appropriately
		if ($this->locationId) {
			$view['breadcrumb'] = array(
				ee('CP/URL')->make('addons/settings/treasury')->compile() => lang('treasury')
			);

			$view['heading'] = $this->locationModel->name;
		} else {
			$view['heading'] = lang('treasury');
		}

		// File filter options
		$fileFilterOptions = array();

		// Filter by location ID if applicable
		if ($this->locationId) {
			$fileFilterOptions['location_id'] = $this->locationId;
			$filesAPI->filter('location_id', $this->locationId);
		}

		// Set sorting and ordering
		$fileFilterOptions['order'] = ee()->input->get('order', true) ?: 'upload_date';
		$fileFilterOptions['sort'] =  ee()->input->get('sort', true) ?: 'desc';
		$filesAPI->order($fileFilterOptions['order'], $fileFilterOptions['sort']);

		// Run search if requested
		$fileSearch = $this->search;
		if ($this->search) {
			$filesAPI->search($this->search);
		} else {
			$fileSearch = ee()->input->get('search', true) ?: null;
			if ($fileSearch) {
				$filesAPI->search($fileSearch);
			}
		}

		// Get total files in this location
		$totalFilesInLocation = $filesAPI->getCount();

		// Set default limit
		$defaultLimit = $modal ? 10 : 25;

		// Set limit
		$fileFilterOptions['limit'] = ee()->input->get('limit', true) ?: $defaultLimit;
		$filesAPI->limit($fileFilterOptions['limit']);

		// Set page number
		$pageNumber = ee()->input->get('page', true) ?: 1;

		// Set the offset
		$fileFilterOptions['offset'] = ($pageNumber - 1) * $fileFilterOptions['limit'];
		$filesAPI->offset($fileFilterOptions['offset']);

		// Check if images only requested
		if (ee()->input->get('imagesOnly') === 'true') {
			$filesAPI->filter('is_image', 'y');
		}

		// Get files with filter options set
		$files = $filesAPI->getFiles();

		// Get this page's URL
		if ($this->locationId) {
			$pageUrl = "addons/settings/treasury/showLocation/{$this->locationId}";
			$pageMethod = "showLocation/{$this->locationId}";
		} else {
			$pageUrl = 'addons/settings/treasury';
			$pageMethod = '';
		}

		// Setup Pagination
		$pagination = false;

		$paginationUrl = ee('CP/URL', $pageUrl)
			->setQueryStringVariable('limit', $fileFilterOptions['limit'])
			->setQueryStringVariable('order', $fileFilterOptions['order'])
			->setQueryStringVariable('sort', $fileFilterOptions['sort']);

		if ($modal) {
			$paginationUrl->setQueryStringVariable('modal', 'true');
		}

		if ($fileSearch) {
			$paginationUrl->setQueryStringVariable('search', $fileSearch);
		}

		if ($totalFilesInLocation > $fileFilterOptions['limit']) {
			/** @var Pagination $paginationObj */
			$paginationObj = ee('CP/Pagination', $totalFilesInLocation);

			$paginationObj->perPage($fileFilterOptions['limit']);

			$paginationObj->currentPage($pageNumber);

			$pagination = $paginationObj->render($paginationUrl);
		}

		// Render view
		$view['body'] = ee('View')->make('treasury:locations')->render(array(
			'modal' => $modal,
			'heading' => $this->locationId ?
				$this->locationModel->name :
				lang('all_locations'),
			'locationId' => $this->locationId,
			'locationsCollection' => $locationsCollection,
			'files' => $files,
			'totalFilesInLocation' => $totalFilesInLocation,
			'fileFilterOptions' => $fileFilterOptions,
			'pageUrl' => $pageUrl,
			'pageMethod' => $pageMethod,
			'pagination' => $pagination,
			'pageNumber' => $pageNumber,
			'fileSearch' => $fileSearch,
			'limitOptions' => array(
				10,
				25,
				50,
				75,
				100,
				150
			)
		));

		// If this is a modal request, exit with only the view
		if ($modal) {
			exit($view['body']);
		}

		// Return view
		return $view;
	}
}
