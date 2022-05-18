<?php

namespace BuzzingPixel\Treasury\Service\FilePicker;

use EllisLab\ExpressionEngine\Service\URL\URLFactory;
use BuzzingPixel\Treasury\Service\LocationsService;
use BuzzingPixel\Treasury\Service\CP\Assets as CPAssets;
use BuzzingPixel\Treasury\Service\CP\FilePickerModal as CPFilePickerModal;

class FilePicker
{
	const CONTROLLER = 'addons/settings/treasury/showLocation';

	/**
	 * EE CP/URL instance
	 */
	protected $url;

	/**
	 * Directories
	 */
	protected $location = 'all';

	/**
	 * Images Only
	 */
	protected $imagesOnly = false;

	/**
	 * Constructor
	 *
	 * @param UrlFactory $url
	 */
	public function __construct(URLFactory $url)
	{
		$this->url = $url;

		// Make sure CP assets are set
		CPAssets::set();

		// Make sure modal is set
		CPFilePickerModal::set();
	}

	/**
	 * Set the allowed directories
	 *
	 * @param String $dirs Allowed directories
	 * @return self
	 */
	public function setlocation($dir)
	{
		$this->location = $dir;
		return $this;
	}

	/**
	 * Set the allowed directories
	 *
	 * @param bool $bool
	 * @return self
	 */
	public function imagesOnly($bool = true)
	{
		$this->imagesOnly = $bool;
		return $this;
	}

	/**
	 * Get a CP\URL instance that points to the filepicker endpoint
	 *
	 * @return CP\URL
	 */
	public function getUrl()
	{
		// Set initially controller url
		$controller = self::CONTROLLER;

		// If the location is not all, we need to get the location ID
		if ($this->location !== 'all') {
			// Get the location model
			$locationsService = LocationsService::getInstance();
			$locationModel = $locationsService->{$this->location};

			// If the location does not exist, return null
			if (! $locationModel) {
				return null;
			}

			// Add the location ID to the controller URL
			$controller .= "/{$locationModel->id}";
		}

		// Set the modal query string
		$qs = array('modal' => 'true');

		if ($this->imagesOnly) {
			$qs['imagesOnly'] = 'true';
		}

		// Return the EE URL instance
		return $this->url->make($controller, $qs);
	}

	/**
	 * Get a new Link instance
	 *
	 * @param string $text The link text [optional]
	 * @return Link
	 */
	public function getLink($text = null)
	{
		// Get new link instance
		$link = new Link($this);

		// If $text is not null, set it
		if ($text !== null) {
			$link->setText($text);
		}

		// Return the link instance
		return $link;
	}
}
