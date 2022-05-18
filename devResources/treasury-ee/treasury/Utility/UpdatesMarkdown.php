<?php

namespace BuzzingPixel\Treasury\Utility;

class UpdatesMarkdown
{
	public static function process($string)
	{
		$itemsToRemove = array();

		if (gettype($string) === 'array') {
			foreach ($string as $val) {
				if (strpos($val, '#') === 0) {
					$itemsToRemove[] = '[' . substr($val, 2) . '] ';
				}
			}

			$string = html_entity_decode(implode("\n\n", $string), ENT_QUOTES);
		}

		ee()->load->library('typography');
		ee()->typography->initialize();

		foreach ($itemsToRemove as $item) {
			$string = str_replace($item, '', $string);
		}

		return ee()->typography->markdown($string);
	}
}
