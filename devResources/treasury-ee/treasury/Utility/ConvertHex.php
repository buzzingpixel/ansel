<?php

namespace BuzzingPixel\Treasury\Utility;

class ConvertHex
{
	/**
	 * Convert HEX color to RGB
	 *
	 * @param string $hex
	 * @return bool|array
	 */
	public static function process($hex)
	{
		// Make sure this is a hex value
		if (strlen($hex) !== 6) {
			return false;
		}

		list($r, $g, $b) = array(
			$hex[0] . $hex[1],
			$hex[2] . $hex[3],
			$hex[4] . $hex[5]
		);

		return array(
			'r' => hexdec($r),
			'g' => hexdec($g),
			'b' => hexdec($b)
		);
	}
}
