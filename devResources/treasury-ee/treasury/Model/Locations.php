<?php

namespace BuzzingPixel\Treasury\Model;

use BuzzingPixel\Treasury\Service\Data\Base;

class Locations extends Base
{
	// Model properties
	protected $id = 'int';
	protected $site_id = 'int';
	protected $name = 'string';
	protected $handle = 'string';
	protected $type = 'string';
	protected $allowed_file_types = 'string';
	protected $url = 'string';
	protected $full_url = 'string';
	protected $path = 'string';
	protected $access_key_id = 'string';
	protected $secret_access_key = 'string';
	protected $bucket_region = 'string';
	protected $bucket = 'string';
	protected $subfolder = 'string';
	protected $server = 'string';
	protected $username = 'string';
	protected $password = 'string';
	protected $private_key = 'string';
	protected $private_key_path = 'string';
	protected $use_config_private_key_path = 'bool';
	protected $port = 'int';
	protected $remote_path = 'string';

	/**
	 * On full_url get
	 */
	protected function full_url__onGet()
	{
		if ($this->full_url) {
			return $this->full_url;
		}

		$this->full_url = $this->url;

		if ($this->subfolder) {
			$this->full_url .= $this->subfolder . '/';
		}

		return $this->full_url;
	}

	/**
	 * Normalize URL on set
	 *
	 * @param string $val
	 * @return string
	 */
	protected function url__onSet($val)
	{
		return rtrim($val, '/') . '/';
	}

	/**
	 * Normalize path on set
	 *
	 * @param string $val
	 * @return string
	 */
	protected function path__onSet($val)
	{
		return rtrim($val, '/') . '/';
	}

	/**
	 * Normalize path on set
	 *
	 * @param string $val
	 * @return string
	 */
	protected function remote_path__onSet($val)
	{
		return rtrim($val, '/') . '/';
	}

	/**
	 * Normalize subfolder on set
	 *
	 * @param string $val
	 * @return string
	 */
	protected function subfolder__onSet($val)
	{
		return ltrim(rtrim($val, '/'), '/');
	}
}
