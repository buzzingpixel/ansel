<?php

namespace BuzzingPixel\Treasury\Service;

use BuzzingPixel\Treasury\Service\Settings;

class Updates
{
	/**
	 * Check for updates
	 */
	public static function getNumber()
	{
		// Run get feed method to make sure updates available is up to date
		self::getFeed();

		// Get settings
		$settings = Settings::getInstance();

		return (int) $settings->updates_available;
	}

	/**
	 * Check the feed for updates
	 *
	 * @param bool $bypassCache
	 */
	public static function getFeed($bypassCache = false)
	{
		// Get settings
		$settings = Settings::getInstance();

		// Update information if applicable
		if ($settings->check_for_updates < time() || $bypassCache === true) {
			// Get feed
			$feed = file_get_contents(
				'https://buzzingpixel.com/software/treasury/changelog/feed'
			);

			// Parse feed into json
			$json = json_decode($feed, true) ?: array();

			// Start a running variable for updates available
			$updatesAvailable = 0;

			// Loop through the feed to find updates
			foreach ($json as $key => $update) {
				if (version_compare($update['version'], TREASURY_VER, '>')) {
					$json[$key]['new'] = true;
					$updatesAvailable++;
				} else {
					$json[$key]['new'] = false;
				}
			}

			// Save json to settings
			$settings->update_feed = json_encode($json);

			// Set the number of updates availble
			$settings->updates_available = $updatesAvailable;

			// Increment the check timer
			$settings->check_for_updates = strtotime('+1 day', time());

			// Save settings
			$settings->save();
		} else {
			$json = json_decode($settings->update_feed, true);
		}

		return $json;
	}
}
