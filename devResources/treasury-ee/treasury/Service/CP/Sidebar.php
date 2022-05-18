<?php

namespace BuzzingPixel\Treasury\Service\CP;

use BuzzingPixel\Treasury\Service\Updates;

class Sidebar
{
	/**
	 * Render the sidebar
	 */
	public static function render()
	{
		// Get the user's group id
		$userGroup = (int) ee()->session->userdata('group_id');

		// Create the sidebar
		$sidebar = ee('CP/Sidebar')->make();

		// Add the heading
		$header = $sidebar->addHeader(lang('locations'));

		// If user is SuperAdmin, add "new" button
		if ($userGroup === 1) {
			$header->withButton(
				lang('new'),
				ee('CP/URL')->make('addons/settings/treasury/editLocation')
			);
		}

		// Add list
		$list = $header->addFolderList('locations_list');

		// Set up the remove url
		$list->withRemoveUrl(ee('CP/URL')->make(
			'addons/settings/treasury/removeLocation/'
		));

		// Get locations
		$locations = ee('treasury:LocationsAPI')->getAllLocations();

		// If there are no results, display no results text
		if (! $locations->count()) {
			$list->withNoResultsText(lang('no_locations_found'));
		}

		// Add all items link
		if ($locations->count()) {
			$item = $list->addItem(
				lang('all_locations'),
				ee('CP/URL')->make('addons/settings/treasury')
			);

			// Do not show remove link
			$item->cannotEdit();

			// Do not show edit link
			$item->cannotRemove();
		}

		// Loop through the locations
		foreach ($locations as $location) {
			$item = $list->addItem($location->name, ee('CP/URL')->make(
				"addons/settings/treasury/showLocation/{$location->id}"
			));

			// Set the item identification
			$item->identifiedBy($location->id);

			// Check if user is super admin
			if ($userGroup !== 1) {
				// Do not show remove link
				$item->cannotEdit();

				// Do not show edit link
				$item->cannotRemove();

				// Continue to the next item
				continue;
			}

			// Set up edit url
			$item->withEditurl(ee('CP/URL')->make(
				"addons/settings/treasury/editLocation/{$location->id}"
			));

			// Set up the remove confirmation message
			$item->withRemoveConfirmation(
				lang('location') .
				': ' .
				'<b>' . $location->name . '</b>' .
				' &mdash; ' .
				lang('deleting_location_message')
			);
		}

		// If user is SuperAdmin, add Settings and Updates
		if ($userGroup === 1) {
			// Add settings
			$header = $sidebar->addHeader(lang('settings'));

			// With URL
			$header->withUrl(ee('CP/URL')->make(
				"addons/settings/treasury/settings"
			));

			// Get number of updates available
			Updates::getFeed();
			$number = Updates::getNumber();

			// Check the number of updates available
			if ($number > 0) {
				$updatesTitle = lang('updates') . " ({$number})";
			} else {
				$updatesTitle = lang('updates');
			}

			// Add updates
			$header = $sidebar->addHeader($updatesTitle);

			// With URL
			$header->withUrl(ee('CP/URL')->make(
				"addons/settings/treasury/updates"
			));
		}
	}
}
