<?php

namespace BuzzingPixel\Treasury\Service;

class Config
{
	/**
	 * Get treasury config item
	 *
	 * @param string $name
	 * @param string $levelOneItem
	 * @param string $levelTwoItem
	 */
	public static function get($name, $levelOneItem = false, $levelTwoItem = false)
	{
		$config = ee()->config->item('treasury');

		if (! $levelOneItem && isset($config[$name])) {
			return $config[$name];
		}

		if (! $levelTwoItem && $levelOneItem && isset($config[$name][$levelOneItem])) {
			return $config[$name][$levelOneItem];
		}

		if ($levelOneItem && isset($config[$name][$levelOneItem][$levelTwoItem])) {
			return $config[$name][$levelOneItem][$levelTwoItem];
		}

		return null;
	}
}
