<?php

namespace BuzzingPixel\Treasury\Utility;

class Form
{
	/**
	 * Form Open
	 *
	 * @param false|string $method
	 * @param array $attributes Optional
	 * @param false|array $params
	 * @param array $hiddenFields Optional
	 */
	public static function open($method = false, $attributes = array(), $params = array(), $hiddenFields = array())
	{
		$uri = 'addons/settings/treasury';

		if ($method) {
			$uri .= '/' . $method;
		}

		ee()->load->helper('form');

		return form_open(
			ee('CP/URL')->make($uri, $params), $attributes, $hiddenFields
		);
	}

	/**
	 * Form Close
	 */
	public static function close()
	{
		return '</form>';
	}
}
