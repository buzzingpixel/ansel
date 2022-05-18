<?php

namespace BuzzingPixel\Treasury\Service;

class TagNoResults
{
	/**
	 * Process no results
	 *
	 * @param string $tagData
	 * @param string $namespace
	 */
	public static function process($tagData, $namespace = '')
	{
		$namespace = $namespace ? "{$namespace}:" : '';
		$ld = LD;
		$rd = RD;
		$regex = "#{$ld}if {$namespace}no_results{$rd}(.*?){$ld}/if{$rd}#s";

		if (
			is_string($tagData) &&
			preg_match($regex, $tagData, $matches)
		) {
			return $matches[1];
		}

		return false;
	}
}
