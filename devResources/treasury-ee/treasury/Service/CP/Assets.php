<?php

namespace BuzzingPixel\Treasury\Service\CP;

class Assets
{
	/**
	 * Set assets
	 *
	 * @param string $pageType
	 */
	public static function set($pageType = false)
	{
		// Make sure CSS is set
		if (! ee()->session->cache('treasury', 'cssSet')) {
			$cssPath = PATH_THIRD_THEMES . 'treasury/css/style.min.css';

			if (is_file($cssPath)) {
				$cssFileTime = filemtime($cssPath);
			} else {
				$cssFileTime = uniqid();
			}

			$css = URL_THIRD_THEMES . "treasury/css/style.min.css?v={$cssFileTime}";
			ee()->cp->add_to_head("<link rel=\"stylesheet\" href=\"{$css}\">");

			// Start styles
			$css = '<style type="text/css">';

			// Set close button
			$closeBtn = URL_THIRD_THEMES . 'treasury/img/sprites/close-button.png';
			$closeBtn2x = URL_THIRD_THEMES . 'treasury/img/sprites/close-button-2x.png';
			$css .= ".treasury-close-btn {background-image: url({$closeBtn});}";
			$css .= "@media only screen and (-webkit-min-device-pixel-ratio: 1.3), (min--moz-device-pixel-ratio: 1.3), (min-resolution: 1.3dppx) {";
			$css .= ".treasury-close-btn {background-image: url({$closeBtn2x});}";
			$css .= "}";

			// End styles
			$css .= '</style>';

			// Send the CSS output to the browser
			ee()->cp->add_to_head($css);

			ee()->session->set_cache('treasury', 'cssSet', true);
		}

		// Make sure TREASURY is defined
		if (! ee()->session->cache('treasury', 'jsSet')) {
			$jsPath = PATH_THIRD_THEMES . 'treasury/js/script.min.js';

			if (is_file($jsPath)) {
				$jsFileTime = filemtime($jsPath);
			} else {
				$jsFileTime = uniqid();
			}

			$js = URL_THIRD_THEMES . "treasury/js/script.min.js?v={$jsFileTime}";
			ee()->cp->add_to_foot(
				"<script type=\"text/javascript\" src=\"{$js}\"></script>"
			);

			ee()->javascript->output(
				"window.TREASURY = window.TREASURY || {};" .
				"window.TREASURY.vars = window.TREASURY.vars || {};"
			);

			ee()->session->set_cache('treasury', 'jsSet', true);
		}

		// Set page type
		if ($pageType) {
			ee()->javascript->output(
				"window.TREASURY.vars.pageType = '{$pageType}';"
			);
		}
	}
}
