<?php

namespace BuzzingPixel\Treasury\Service;

use BuzzingPixel\Treasury\Service\Settings;

class License
{
	/**
	 * Check lidense
	 */
	public static function check()
	{
		// Make sure this is not a modal request
		if (ee()->input->get('modal') === 'true') {
			return;
		}

		// Get settings
		$settings = Settings::getInstance();

		// Check the license key and make a banner alert if no license key
		if (! $settings->license_key) {
			// Get the license link
			$link = ee('CP/URL', 'addons/settings/treasury/settings');

			// Set link in lang
			$text = str_replace(
				"{{startlink}}",
				"<a href=\"{$link}\">",
				lang('no_license')
			);
			$text = str_replace(
				'{{endlink}}',
				'</a>',
				$text
			);

			// Show the banner
			ee('CP/Alert')->makeBanner('treasury-no-license')
				->asWarning()
				->cannotClose()
				->withTitle(lang('no_license_title'))
				->addToBody($text)
				->now();
		}

		// Check if it's time to phone home
		$phoneHome = 'false';

		if (
			$settings->phone_home < time() ||
			end(ee()->uri->segments) === 'license'
		) {
			$phoneHome = 'true';
		}

		// Set the license increment Url
		$licenseIncrementUri = ee('CP/URL', 'addons/settings/treasury/incrementPhoneHome')->compile();

		$urlThirdThemes = URL_THIRD_THEMES;

		// Output the javascript
		ee()->javascript->output(
			"window.TREASURY = window.TREASURY || {};" .
			"TREASURY.vars = TREASURY.vars || {};" .
			"TREASURY.lang = TREASURY.lang || {};" .
			"TREASURY.vars.licenseKey = '{$settings->license_key}';" .
			"TREASURY.vars.phoneHome = {$phoneHome};" .
			"TREASURY.vars.licenseIncrementUri = '{$licenseIncrementUri}';" .
			"TREASURY.vars.urlThirdThemes = '{$urlThirdThemes}';"
		);
	}
}
