<?php

namespace BuzzingPixel\Treasury\Model;

use BuzzingPixel\Treasury\Service\Data\Base;
use BuzzingPixel\Treasury\Service\LocationsService;

class Files extends Base
{
	// Standard date format
	const STANDARD_DATE_FORMAT = 'n/j/Y g:i a';

	// Model properties
	protected $id = 'int';
	protected $site_id = 'int';
	protected $title = 'string';
	protected $location_id = 'string';
	protected $is_image = 'bool';
	protected $mime_type = 'string';
	protected $file_name = 'string';
	protected $basename = 'string';
	protected $filename = 'string';
	protected $extension = 'string';
	protected $file_size = 'int';
	protected $description = 'string';
	protected $uploaded_by_member_id = 'int';
	protected $upload_date = 'int';
	protected $modified_by_member_id = 'int';
	protected $modified_date = 'int';
	protected $height = 'int';
	protected $width = 'int';

	protected $location = 'object';

	protected $file_url = 'string';
	protected $thumb_url = 'string';

	/**
	 * On location get
	 *
	 * @param string $val Existing value
	 * @return string
	 */
	protected function basename__onGet($val)
	{
		if ($val) {
			return $val;
		}

		$this->basename = $this->file_name;

		return $this->basename;
	}

	/**
	 * On location get
	 *
	 * @param string $val Existing value
	 * @return string
	 */
	protected function filename__onGet($val)
	{
		if ($val) {
			return $val;
		}

		$pathinfo = pathinfo($this->file_name);

		$this->filename = $pathinfo['filename'];

		return $this->filename;
	}

	/**
	 * On location get
	 *
	 * @param string $val Existing value
	 * @return object \BuzzingPixel\Treasury\Model\Locations
	 */
	protected function location__onGet($val)
	{
		$this->populateLocation();
		return $this->location;
	}

	/**
	 * On file url get
	 *
	 * @param string $val Existing value
	 * @return string
	 */
	protected function file_url__onGet($val)
	{
		if ($val) {
			return $val;
		}

		$this->populateLocation();

		// Set subfolder
		$subfolder = '';
		if ($this->location->subfolder) {
			$subfolder = ltrim(rtrim($this->location->subfolder, '/'), '/') . '/';
		}

		$this->file_url = $this->location->url . $subfolder . rawurlencode($this->file_name);

		return $this->file_url;
	}

	/**
	 * On thumb url get
	 *
	 * @param string $val Existing value
	 * @return string
	 */
	protected function thumb_url__onGet($val)
	{
		if ($val) {
			return $val;
		}

		$this->populateLocation();

		// Set subfolder
		$subfolder = '';
		if ($this->location->subfolder) {
			$subfolder = ltrim(rtrim($this->location->subfolder, '/'), '/') . '/';
		}

		$thumbUrl = $this->location->url . $subfolder . '_thumbs/' . $this->file_name;

		// If local check thumb path, else check url
		if ($this->location->type === 'local') {
			// Set the file path
			$path = $this->location->path . '_thumbs/' . $this->file_name;

			if (! file_exists($path)) {
				$thumbUrl = false;
			}
		} elseif ($this->location->type === 'amazon_s3') {
			if (! $this->is_image) {
				$thumbUrl = false;
			}
		}

		// If there is a thumb url, re-put it together with rawurlencode
		if ($thumbUrl) {
			$thumbUrl = $this->location->url . $subfolder . '_thumbs/' . rawurlencode($this->file_name);
		}

		// Check if we still need to get a thumbnail
		if (! $thumbUrl) {
			// Check the extension path
			$path = PATH_THIRD_THEMES . 'treasury/img/icons/' . $this->extension . '.png';

			// Check if file exists
			if (is_file($path)) {
				$thumbUrl = URL_THIRD_THEMES . 'treasury/img/icons/' . $this->extension . '.png';
			}
		}

		// Check if we still need a thumbnail
		if (! $thumbUrl) {
			$thumbUrl = URL_THIRD_THEMES . 'treasury/img/icons/generic.png';
		}

		$this->thumb_url = $thumbUrl;

		return $this->thumb_url;
	}

	/**
	 * Format upload date
	 *
	 * @return string
	 */
	public function upload_date($format = self::STANDARD_DATE_FORMAT)
	{
		return date($format, $this->upload_date);
	}

	/**
	 * Format modified date
	 *
	 * @return string
	 */
	public function modified_date($format = self::STANDARD_DATE_FORMAT)
	{
		return date($format, $this->upload_date);
	}

	/**
	 * Populate location data
	 *
	 * @return self
	 */
	public function populateLocation()
	{
		// If location has already been set, return
		if (
			is_a($this->location, '\\BuzzingPixel\\Treasury\\Model\\Locations')
		) {
			return;
		}

		// Get the location
		$locationsService = LocationsService::getInstance();
		$this->location = $locationsService->getLocationById($this->location_id);

		return $this;
	}
}
