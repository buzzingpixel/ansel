<?php

namespace BuzzingPixel\Treasury\API;

use EllisLab\ExpressionEngine\Service\URL\URLFactory;
use BuzzingPixel\Treasury\Service\FilePicker\FilePicker as FilePickerService;

class FilePicker
{
	/**
	 * EE CP/URL instance
	 */
	protected $url;

	/**
	 * Constructor
	 *
	 * @param UrlFactory $url
	 */
	public function __construct(UrlFactory $url)
	{
		$this->url = $url;
	}

	/**
	 * Construct a filepicker instance
	 *
	 * @param String $dir Allowed directory
	 * @return FilePicker
	 */
	public function make($dir = 'all')
	{
		$fp = new FilePickerService($this->url);
		$fp->setLocation($dir);

		return $fp;
	}
}
